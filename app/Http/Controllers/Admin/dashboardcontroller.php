<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\Guru;
use App\Models\StafTu;
use App\Models\Satpam;
use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\JurnalMengajar;
use App\Models\JurnalDetailKetidakhadiran;
use App\Models\DispenSiswa;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController
{
    public function index()
    {
        Carbon::setLocale('id');
        $today = Carbon::today();
        $todayDate = $today->format('Y-m-d');

        $hariMap = [
            'Monday'    => 'Senin',
            'Tuesday'   => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday'  => 'Kamis',
            'Friday'    => 'Jumat',
            'Saturday'  => 'Sabtu',
            'Sunday'    => 'Minggu',
        ];
        $namaHariIni = $hariMap[$today->format('l')] ?? 'Senin';

        // 1. STATISTIK UTAMA
        $totalSiswa = Siswa::count();
        $totalGuru = Guru::count();
        $totalStaf = StafTu::count();
        $totalPegawai = $totalGuru + $totalStaf + Satpam::count();
        $totalJadwal = Jadwal::count();
        $totalJadwalHariIni = Jadwal::where('hari', $namaHariIni)->count();
        $jurnalTerisiHariIni = JurnalMengajar::whereDate('tanggal', $todayDate)->count();
        $persentaseKepatuhan = $totalJadwalHariIni > 0 ? round(($jurnalTerisiHariIni / $totalJadwalHariIni) * 100) : 0;

        // 2. DATA TABEL JADWAL HARI INI (Diurutkan dari yang ALPA lebih dulu!)
        $jadwalHariIniUnsorted = Jadwal::with(['kelas', 'guru', 'mapel', 'ruangan'])
            ->where('hari', $namaHariIni)
            ->get()
            ->map(function ($j) use ($todayDate) {
                $jurnal = JurnalMengajar::where('id_jadwal', $j->id_jadwal)
                    ->whereDate('tanggal', $todayDate)
                    ->first();

                if ($jurnal) {
                    $j->status_jurnal = 'Selesai';
                    $j->sort_order = 4;
                } else {
                    $izin = \App\Models\IzinGuru::where('id_guru', $j->id_guru)
                        ->where('status_akhir', 'Disetujui')
                        ->whereDate('tanggal_mulai', '<=', $todayDate)
                        ->whereDate('tanggal_selesai', '>=', $todayDate)
                        ->first();

                    if ($izin) {
                        $j->status_jurnal = $izin->alasan . ' (Sah)';
                        $j->sort_order = 3;
                    } else {
                        // Jika jam mengajar telah lewat / telat mengisi -> ALPA
                        $statusWaktu = $j->statusWaktuMengajar();
                        if ($statusWaktu === 'telat') {
                            $j->status_jurnal = 'Alpa';
                            $j->sort_order = 1; // Prioritas utama di paling atas
                        } else {
                            $j->status_jurnal = 'Terjadwal';
                            $j->sort_order = 2;
                        }
                    }
                }

                return $j;
            });

        // Urutkan: ALPA (1) -> Terjadwal (2) -> Izin Sah (3) -> Selesai (4)
        $jadwalHariIni = $jadwalHariIniUnsorted->sortBy(function ($item) {
            return sprintf('%d-%02d', $item->sort_order, $item->jam_mulai);
        })->values();

        // 3. PERLU TINDAKAN & LIST GURU ALPA / BELUM ISI
        $kemarin = Carbon::yesterday();
        $namaHariKemarin = $hariMap[$kemarin->format('l')] ?? null;
        $guruBelumIsiKemarin = 0;
        $listGuruBelumIsiKemarin = collect();

        if ($namaHariKemarin && in_array($namaHariKemarin, ['Senin','Selasa','Rabu','Kamis','Jumat'])) {
            $jadwalKemarin = Jadwal::with(['guru', 'kelas', 'mapel', 'ruangan'])
                ->where('hari', $namaHariKemarin)
                ->get();
            $jurnalKemarinIds = JurnalMengajar::whereDate('tanggal', $kemarin->format('Y-m-d'))->pluck('id_jadwal')->toArray();

            $listGuruBelumIsiKemarin = $jadwalKemarin->filter(function ($j) use ($jurnalKemarinIds) {
                return !in_array($j->id_jadwal, $jurnalKemarinIds);
            })->values();

            $guruBelumIsiKemarin = $listGuruBelumIsiKemarin->count();
        }

        // List Guru Alpa Hari Ini
        $listGuruAlpaHariIni = $jadwalHariIni->filter(function ($j) {
            return $j->status_jurnal === 'Alpa';
        })->values();
        $guruAlpaHariIni = $listGuruAlpaHariIni->pluck('id_guru')->unique()->count();

        // 4. DATA REKAP ABSENSI SISWA HARI INI
        $siswaSakitHariIni = JurnalDetailKetidakhadiran::where('keterangan', 'Sakit')
            ->whereHas('jurnal', function ($q) use ($todayDate) {
                $q->whereDate('tanggal', $todayDate);
            })->count();

        $siswaIzinHariIni = JurnalDetailKetidakhadiran::where('keterangan', 'Izin')
            ->whereHas('jurnal', function ($q) use ($todayDate) {
                $q->whereDate('tanggal', $todayDate);
            })->count();
        
        $dispenActiveCount = DispenSiswa::whereDate('tanggal', $todayDate)
            ->whereIn('status', ['Disetujui', 'Sedang di Luar'])
            ->count();
        $siswaIzinTotal = $siswaIzinHariIni + $dispenActiveCount;

        $siswaAlpaHariIni = JurnalDetailKetidakhadiran::where('keterangan', 'Alpa')
            ->whereHas('jurnal', function ($q) use ($todayDate) {
                $q->whereDate('tanggal', $todayDate);
            })->count();

        $siswaHadirHariIni = max(0, $totalSiswa - ($siswaSakitHariIni + $siswaIzinTotal + $siswaAlpaHariIni));

        // 5. PENGISIAN JURNAL PER KELAS MINGGU INI
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();

        $pengisianPerKelas = Kelas::take(5)->get()->map(function ($kelas) use ($startOfWeek, $endOfWeek) {
            $jadwalKelasIds = Jadwal::where('id_kelas', $kelas->id_kelas)->pluck('id_jadwal');
            $totalSesiMingguIni = $jadwalKelasIds->count();

            $sesiTerisi = JurnalMengajar::whereIn('id_jadwal', $jadwalKelasIds)
                ->whereBetween('tanggal', [$startOfWeek->format('Y-m-d'), $endOfWeek->format('Y-m-d')])
                ->count();

            $persentase = $totalSesiMingguIni > 0 ? round(($sesiTerisi / $totalSesiMingguIni) * 100) : 0;

            return [
                'nama_kelas' => $kelas->nama_kelas,
                'persentase' => min($persentase, 100),
            ];
        });

        $tanggalHariIniTeks = $today->translatedFormat('l, j F Y');

        return view('admin.dashboard', compact(
            'tanggalHariIniTeks',
            'namaHariIni',
            'totalSiswa',
            'totalPegawai',
            'totalGuru',
            'totalStaf',
            'totalJadwal',
            'totalJadwalHariIni',
            'jurnalTerisiHariIni',
            'persentaseKepatuhan',
            'jadwalHariIni',
            'guruBelumIsiKemarin',
            'listGuruBelumIsiKemarin',
            'guruAlpaHariIni',
            'listGuruAlpaHariIni',
            'siswaHadirHariIni',
            'siswaSakitHariIni',
            'siswaIzinTotal',
            'siswaAlpaHariIni',
            'pengisianPerKelas'
        ));
    }
}
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

        // 2. DATA TABEL JADWAL HARI INI (Semua sesi hari ini diambil untuk di-slide per 10 data)
        $jadwalHariIni = Jadwal::with(['kelas', 'guru', 'mapel', 'ruangan'])
            ->where('hari', $namaHariIni)
            ->orderBy('jam_mulai', 'asc')
            ->get()
            ->map(function ($j) use ($todayDate) {
                $jurnal = JurnalMengajar::where('id_jadwal', $j->id_jadwal)
                    ->whereDate('tanggal', $todayDate)
                    ->first();

                $j->status_jurnal = $jurnal ? 'Selesai' : 'Terjadwal';

                return $j;
            });

        // 3. PERLU TINDAKAN
        // A. Guru belum isi jurnal kemarin
        $kemarin = Carbon::yesterday();
        $namaHariKemarin = $hariMap[$kemarin->format('l')] ?? null;
        $guruBelumIsiKemarin = 0;
        if ($namaHariKemarin && in_array($namaHariKemarin, ['Senin','Selasa','Rabu','Kamis','Jumat'])) {
            $jadwalKemarinIds = Jadwal::where('hari', $namaHariKemarin)->pluck('id_jadwal');
            $jurnalKemarinIds = JurnalMengajar::whereDate('tanggal', $kemarin->format('Y-m-d'))->pluck('id_jadwal');
            $guruBelumIsiKemarin = $jadwalKemarinIds->diff($jurnalKemarinIds)->count();
        }

        // B. Siswa alpa hari ini
        $siswaAlpaHariIni = JurnalDetailKetidakhadiran::where('keterangan', 'Alpa')
            ->whereHas('jurnal', function ($q) use ($todayDate) {
                $q->whereDate('tanggal', $todayDate);
            })->count();

        // C. Deteksi bentrok ruangan
        $bentrokRuangan = Jadwal::with(['ruangan', 'kelas'])
            ->select('hari', 'id_ruangan', 'jam_mulai', DB::raw('COUNT(*) as total'))
            ->groupBy('hari', 'id_ruangan', 'jam_mulai')
            ->having('total', '>', 1)
            ->first();

        // 4. PENGISIAN JURNAL PER KELAS MINGGU INI
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
            'siswaAlpaHariIni',
            'bentrokRuangan',
            'pengisianPerKelas'
        ));
    }
}
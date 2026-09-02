<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\Guru;
use App\Models\JurnalMengajar;
use App\Models\JurnalDetailKetidakhadiran;
use App\Models\IzinGuru;
use Carbon\Carbon;

class RekapJurnalController
{
    public function index(Request $request)
    {
        // 1. Tentukan tanggal filter (default: hari ini)
        $tanggal = $request->input('tanggal', date('Y-m-d'));
        $carbonDate = Carbon::parse($tanggal);
        $filterKelas = $request->input('kelas');

        $hariMap = [
            'Monday'    => 'Senin',
            'Tuesday'   => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday'  => 'Kamis',
            'Friday'    => 'Jumat',
            'Saturday'  => 'Sabtu',
            'Sunday'    => 'Minggu',
        ];
        $namaHari = $hariMap[$carbonDate->format('l')] ?? 'Senin';

        // Daftar kelas untuk dropdown filter
        $daftarKelas = Kelas::orderBy('nama_kelas', 'asc')->get();

        // 2. TABEL A: Jurnal Tersimpan + Bukti Foto pada Tanggal Tersebut
        $qJurnal = JurnalMengajar::with([
            'foto',
            'jadwal.guru',
            'jadwal.kelas',
            'jadwal.mapel',
            'jadwal.ruangan',
            'detailKetidakhadiran.siswa'
        ])
        ->whereDate('tanggal', $tanggal);

        if ($filterKelas) {
            $qJurnal->whereHas('jadwal', function ($q) use ($filterKelas) {
                $q->where('id_kelas', $filterKelas);
            });
        }

        $jurnalTersimpan = $qJurnal->orderBy('dicatat_pada', 'desc')->get();

        // 3. TABEL B: Detail Ketidakhadiran Siswa (Alpa, Sakit, Izin) pada Tanggal Tersebut
        $siswaAbsenList = JurnalDetailKetidakhadiran::with([
            'siswa.kelas',
            'jurnal.jadwal.guru',
            'jurnal.jadwal.mapel'
        ])
        ->whereHas('jurnal', function ($q) use ($tanggal) {
            $q->whereDate('tanggal', $tanggal);
        })
        ->get();

        // 4. TABEL C: Laporan Guru Belum Isi Jurnal (Alpa / Izin Sah / Terjadwal)
        $idJadwalTerisi = $jurnalTersimpan->pluck('id_jadwal')->toArray();

        $qAlpa = Jadwal::with(['guru', 'kelas', 'mapel', 'ruangan'])
            ->where('hari', $namaHari)
            ->whereNotIn('id_jadwal', $idJadwalTerisi);

        if ($filterKelas) {
            $qAlpa->where('id_kelas', $filterKelas);
        }

        $guruAlpaList = $qAlpa->get()
            ->map(function ($ga) use ($tanggal) {
                $isToday = ($tanggal === date('Y-m-d'));
                $isPast = ($tanggal < date('Y-m-d'));

                // Cek Izin Sah
                $izin = IzinGuru::where('id_guru', $ga->id_guru)
                    ->where('status_akhir', 'Disetujui')
                    ->whereDate('tanggal_mulai', '<=', $tanggal)
                    ->whereDate('tanggal_selesai', '>=', $tanggal)
                    ->first();

                if ($izin) {
                    $ga->status_rekap = $izin->alasan . ' (Sah)';
                    $ga->sort_order = 2;
                } else {
                    if ($isPast) {
                        $ga->status_rekap = 'Alpa';
                        $ga->sort_order = 1;
                    } elseif ($isToday) {
                        $statusWaktu = $ga->statusWaktuMengajar();
                        if ($statusWaktu === 'telat') {
                            $ga->status_rekap = 'Alpa';
                            $ga->sort_order = 1;
                        } else {
                            $ga->status_rekap = 'Terjadwal';
                            $ga->sort_order = 3;
                        }
                    } else {
                        $ga->status_rekap = 'Terjadwal';
                        $ga->sort_order = 3;
                    }
                }

                return $ga;
            })
            ->sortBy(function ($item) {
                return sprintf('%d-%02d', $item->sort_order, $item->jam_mulai);
            })
            ->values();

        return view('admin.rekap.index', compact(
            'tanggal',
            'namaHari',
            'filterKelas',
            'daftarKelas',
            'jurnalTersimpan',
            'siswaAbsenList',
            'guruAlpaList'
        ));
    }

    /**
     * Halaman Rekapitulasi Kepatuhan Pengisian Jurnal Guru
     */
    public function kepatuhan(Request $request)
    {
        $search = $request->input('search');

        $startOfWeek = Carbon::now()->startOfWeek(Carbon::MONDAY);
        $endOfWeek = Carbon::now()->endOfWeek(Carbon::FRIDAY);
        $today = Carbon::today();

        $allGuru = Guru::with(['jadwal.kelas', 'jadwal.mapel', 'jadwal.ruangan'])
            ->orderBy('nama_guru')
            ->get();

        $hariList = ['Senin','Selasa','Rabu','Kamis','Jumat'];

        $rekapKepatuhan = $allGuru->map(function ($g) use ($startOfWeek, $today, $hariList) {
            $jadwalGuru = $g->jadwal ?? collect();
            $totalJadwalSeminggu = $jadwalGuru->count();
            
            $sesiSeharusnya = 0;
            $sesiTerisi = 0;
            $rincianTertunggak = [];

            foreach ($jadwalGuru as $j) {
                $dayIndex = array_search($j->hari, $hariList);
                if ($dayIndex === false) continue;
                
                $tanggalJadwal = $startOfWeek->copy()->addDays($dayIndex)->toDateString();
                
                // Jika jadwal sudah lewat atau hari ini
                if ($tanggalJadwal <= $today->toDateString()) {
                    $sesiSeharusnya++;
                    
                    $jurnal = JurnalMengajar::where('id_jadwal', $j->id_jadwal)
                        ->whereDate('tanggal', $tanggalJadwal)
                        ->first();

                    if ($jurnal) {
                        $sesiTerisi++;
                    } else {
                        $izin = IzinGuru::where('id_guru', $g->id_guru)
                            ->where('status_akhir', 'Disetujui')
                            ->whereDate('tanggal_mulai', '<=', $tanggalJadwal)
                            ->whereDate('tanggal_selesai', '>=', $tanggalJadwal)
                            ->first();

                        $rincianTertunggak[] = [
                            'tanggal'   => $tanggalJadwal,
                            'hari'      => $j->hari,
                            'jam'       => "Jam {$j->jam_mulai}-{$j->jam_selesai}",
                            'kelas'     => $j->kelas ? $j->kelas->nama_kelas : '-',
                            'mapel'     => $j->mapel ? $j->mapel->nama_mapel : '-',
                            'ruangan'   => $j->ruangan ? $j->ruangan->nama_ruangan : '-',
                            'keterangan'=> $izin ? 'Izin Resmi (' . $izin->alasan . ')' : 'Alpa / Belum Diisi',
                        ];
                    }
                }
            }

            $persentase = $sesiSeharusnya > 0 ? round(($sesiTerisi / $sesiSeharusnya) * 100) : 100;
            $isPatuh = ($persentase >= 100);

            return [
                'id_guru'            => $g->id_guru,
                'nip'                => $g->nip,
                'nama_guru'          => $g->nama_guru,
                'total_jadwal'       => $totalJadwalSeminggu,
                'sesi_seharusnya'    => $sesiSeharusnya,
                'sesi_terisi'        => $sesiTerisi,
                'sesi_tertunggak'    => count($rincianTertunggak),
                'persentase'         => $persentase,
                'is_patuh'           => $isPatuh,
                'rincian_tertunggak' => $rincianTertunggak,
            ];
        });

        if ($search) {
            $rekapKepatuhan = $rekapKepatuhan->filter(function ($item) use ($search) {
                return stripos($item['nama_guru'], $search) !== false || stripos($item['nip'], $search) !== false;
            });
        }

        $totalGuruCount = $rekapKepatuhan->count();
        $guruPatuhCount = $rekapKepatuhan->where('is_patuh', true)->count();
        $guruTidakPatuhCount = $totalGuruCount - $guruPatuhCount;

        return view('admin.rekap.kepatuhan', compact(
            'rekapKepatuhan',
            'totalGuruCount',
            'guruPatuhCount',
            'guruTidakPatuhCount',
            'search'
        ));
    }

    /**
     * Tampilkan Detail Jurnal Mengajar (Termasuk Foto Bukti & Absensi Siswa)
     */
    public function show($id)
    {
        $jurnal = JurnalMengajar::with([
            'foto',
            'jadwal.guru',
            'jadwal.kelas',
            'jadwal.mapel',
            'jadwal.ruangan',
            'detailKetidakhadiran.siswa'
        ])->findOrFail($id);

        return view('admin.rekap.show', compact('jurnal'));
    }
}
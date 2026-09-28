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
        $tingkat = $request->input('tingkat');

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

        // Daftar kelas untuk dropdown & matriks
        $daftarKelasQuery = Kelas::orderBy('nama_kelas', 'asc');
        if ($tingkat) {
            $daftarKelasQuery->where(function ($k) use ($tingkat) {
                $k->where('nama_kelas', 'like', $tingkat . ' %')
                  ->orWhere('nama_kelas', 'like', $tingkat . '-%')
                  ->orWhere('nama_kelas', 'like', $tingkat . '.%');
            });
        }
        $daftarKelas = $daftarKelasQuery->get();

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

        // 5. MATRIX TIMELINE SESI PER KELAS (VERTICAL BALOK)
        $qJadwalHari = Jadwal::with(['guru', 'kelas', 'mapel', 'ruangan'])
            ->where('hari', $namaHari);
        if ($filterKelas) {
            $qJadwalHari->where('id_kelas', $filterKelas);
        }
        $allJadwalHari = $qJadwalHari->get();

        $jurnalMap = $jurnalTersimpan->keyBy('id_jadwal');

        $approvedIzin = IzinGuru::where('status_akhir', 'Disetujui')
            ->whereDate('tanggal_mulai', '<=', $tanggal)
            ->whereDate('tanggal_selesai', '>=', $tanggal)
            ->get()
            ->keyBy('id_guru');

        $maxJam = max(10, (int)($allJadwalHari->max('jam_selesai') ?? 10));

        $matrixKelas = [];
        $daftarKelasMatrix = $filterKelas ? $daftarKelas->where('id_kelas', $filterKelas) : $daftarKelas;

        foreach ($daftarKelasMatrix as $kls) {
            $sessions = $allJadwalHari->where('id_kelas', $kls->id_kelas)->sortBy('jam_mulai');
            $sessionBlocks = [];

            foreach ($sessions as $s) {
                $jurnal = $jurnalMap->get($s->id_jadwal);
                $hasIzin = $approvedIzin->has($s->id_guru);

                if ($jurnal) {
                    $status = 'terisi'; // Hijau
                    $statusLabel = 'Terisi (Hadir)';
                    $color = 'green';
                } elseif ($hasIzin) {
                    $status = 'izin'; // Kuning
                    $statusLabel = $approvedIzin->get($s->id_guru)->alasan . ' (Izin Sah)';
                    $color = 'yellow';
                } else {
                    $status = 'alpa'; // Merah
                    $statusLabel = 'Belum Mengisi Jurnal / Alpa';
                    $color = 'red';
                }

                $sessionBlocks[] = [
                    'id_jadwal' => $s->id_jadwal,
                    'jam_mulai' => (int)$s->jam_mulai,
                    'jam_selesai' => (int)$s->jam_selesai,
                    'durasi' => (int)($s->jam_selesai - $s->jam_mulai + 1),
                    'nama_guru' => $s->guru ? $s->guru->nama_guru : 'Guru Belum Ditentukan',
                    'nip' => $s->guru ? $s->guru->nip : '-',
                    'nama_mapel' => $s->mapel ? $s->mapel->nama_mapel : 'Mata Pelajaran',
                    'nama_ruangan' => $s->ruangan ? $s->ruangan->nama_ruangan : 'Default Kelas',
                    'status' => $status,
                    'status_label' => $statusLabel,
                    'color' => $color,
                    'materi' => $jurnal ? $jurnal->materi : null,
                    'id_jurnal' => $jurnal ? $jurnal->id_jurnal : null,
                ];
            }

            $matrixKelas[] = [
                'id_kelas' => $kls->id_kelas,
                'nama_kelas' => $kls->nama_kelas,
                'total_sesi' => $sessions->count(),
                'terisi_count' => collect($sessionBlocks)->where('status', 'terisi')->count(),
                'alpa_count' => collect($sessionBlocks)->where('status', 'alpa')->count(),
                'sessions' => $sessionBlocks,
            ];
        }

        // --- CHART DATA PREPARATION ---
        // 1. Pie Chart Data (Selected Day)
        $totalTerisi = $jurnalTersimpan->count();
        $totalAlpa = $guruAlpaList->where('status_rekap', 'Alpa')->count();
        $totalIzin = $guruAlpaList->filter(fn($g) => str_contains($g->status_rekap, 'Sah'))->count();
        $totalTerjadwal = $guruAlpaList->where('status_rekap', 'Terjadwal')->count();
        
        $pieChartData = [
            'Terisi' => $totalTerisi,
            'Alpa' => $totalAlpa,
            'Izin' => $totalIzin,
            'Terjadwal' => $totalTerjadwal
        ];

        // 2. Area Chart Data (Last 7 Days)
        $areaChartLabels = [];
        $areaChartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $d = Carbon::parse($tanggal)->subDays($i)->format('Y-m-d');
            $areaChartLabels[] = Carbon::parse($d)->translatedFormat('d M');
            $queryArea = JurnalMengajar::whereDate('tanggal', $d);
            if ($filterKelas) {
                $queryArea->whereHas('jadwal', function($q) use ($filterKelas) {
                    $q->where('id_kelas', $filterKelas);
                });
            }
            $areaChartData[] = $queryArea->count();
        }
        
        $chartData = [
            'pie' => $pieChartData,
            'area_labels' => $areaChartLabels,
            'area_data' => $areaChartData
        ];

        return view('admin.rekap.index', compact(
            'tanggal',
            'namaHari',
            'filterKelas',
            'tingkat',
            'daftarKelas',
            'jurnalTersimpan',
            'siswaAbsenList',
            'guruAlpaList',
            'chartData',
            'matrixKelas',
            'maxJam'
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
            $rincianSesi = [];
            $sesiTertunggakCount = 0;

            foreach ($jadwalGuru as $j) {
                $dayIndex = array_search($j->hari, $hariList);
                if ($dayIndex === false) continue;
                
                $tanggalJadwal = $startOfWeek->copy()->addDays($dayIndex)->toDateString();
                $isSudahLewatAtauHariIni = ($tanggalJadwal <= $today->toDateString());

                $jurnal = JurnalMengajar::where('id_jadwal', $j->id_jadwal)
                    ->whereDate('tanggal', $tanggalJadwal)
                    ->first();

                $izin = null;
                if (!$jurnal) {
                    $izin = IzinGuru::where('id_guru', $g->id_guru)
                        ->where('status_akhir', 'Disetujui')
                        ->whereDate('tanggal_mulai', '<=', $tanggalJadwal)
                        ->whereDate('tanggal_selesai', '>=', $tanggalJadwal)
                        ->first();
                }

                if ($isSudahLewatAtauHariIni) {
                    $sesiSeharusnya++;
                    if ($jurnal) {
                        $sesiTerisi++;
                        $statusKode = 'terisi';
                        $statusText = 'Sudah Diisi';
                    } elseif ($izin) {
                        $statusKode = 'izin';
                        $statusText = 'Izin Resmi (' . $izin->alasan . ')';
                    } else {
                        $sesiTertunggakCount++;
                        $statusKode = 'tertunggak';
                        $statusText = 'Alpa / Belum Diisi';
                    }
                } else {
                    $statusKode = 'mendatang';
                    $statusText = 'Jadwal Mendatang';
                }

                $rincianSesi[] = [
                    'id_jurnal'  => $jurnal ? $jurnal->id_jurnal : null,
                    'tanggal'    => $tanggalJadwal,
                    'hari'       => $j->hari,
                    'jam'        => "Jam {$j->jam_mulai}-{$j->jam_selesai}",
                    'kelas'      => $j->kelas ? $j->kelas->nama_kelas : '-',
                    'mapel'      => $j->mapel ? $j->mapel->nama_mapel : '-',
                    'ruangan'    => $j->ruangan ? $j->ruangan->nama_ruangan : '-',
                    'status_kode'=> $statusKode,
                    'keterangan' => $statusText,
                ];
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
                'sesi_tertunggak'    => $sesiTertunggakCount,
                'persentase'         => $persentase,
                'is_patuh'           => $isPatuh,
                'rincian_sesi'       => $rincianSesi,
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
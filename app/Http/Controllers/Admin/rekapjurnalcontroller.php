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
use App\Models\EventSekolah;

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

                // Cek Event Sekolah / Pulang Pagi
                $times  = $ga->getWaktuMulaiSelesai();
                $exempt = EventSekolah::cekExempt($tanggal, $times['waktu_mulai'] ?? null);

                if ($exempt['exempt']) {
                    $ga->status_rekap = $exempt['alasan'];
                    $ga->sort_order = 2;
                } elseif ($izin) {
                    $ga->status_rekap = $izin->alasan . ' (Sah)';
                    $ga->sort_order = 2;
                } else {
                    if ($isPast) {
                        $ga->status_rekap = 'Belum Mengisi Jurnal';
                        $ga->sort_order = 1;
                    } elseif ($isToday) {
                        $statusWaktu = $ga->statusWaktuMengajar();
                        if ($statusWaktu === 'telat') {
                            $ga->status_rekap = 'Belum Mengisi Jurnal';
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
                    // Cek event / pulang pagi
                    $times  = $s->getWaktuMulaiSelesai();
                    $exempt = EventSekolah::cekExempt($tanggal, $times['waktu_mulai'] ?? null);
                    if ($exempt['exempt']) {
                        $status = 'exempt'; // Abu-abu
                        $statusLabel = $exempt['alasan'];
                        $color = 'gray';
                    } else {
                        $status = 'alpa'; // Merah
                        $statusLabel = 'Belum Mengisi Jurnal / Alpa';
                        $color = 'red';
                    }
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
        $totalBelumIsi = $guruAlpaList->where('status_rekap', 'Belum Mengisi Jurnal')->count();
        $totalIzin = $guruAlpaList->filter(fn($g) => str_contains($g->status_rekap, 'Sah'))->count();
        $totalTerjadwal = $guruAlpaList->where('status_rekap', 'Terjadwal')->count();
        
        $pieChartData = [
            'Terisi' => $totalTerisi,
            'Belum Mengisi Jurnal' => $totalBelumIsi,
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
     * Tampilkan Detail Jurnal Mengajar
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
        ])
        ->findOrFail($id);

        return view('Admin.rekap.show', compact('jurnal'));
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
                    // Cek event sekolah / pulang pagi
                    $times  = $j->getWaktuMulaiSelesai();
                    $exempt = EventSekolah::cekExempt($tanggalJadwal, $times['waktu_mulai'] ?? null);

                    if ($exempt['exempt']) {
                        // Hari ini ada event/pulang pagi — sesi ini tidak dihitung dalam rekap
                        $statusKode = 'exempt';
                        $statusText = $exempt['alasan'];
                        // Tidak increment $sesiSeharusnya agar tidak mempengaruhi persentase
                    } else {
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
                            $statusText = 'Belum Mengisi Jurnal';
                        }
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
     * Trigger Manual Pengiriman Notifikasi WA Rekap Siswa Alpa ke Orang Tua & Wali Kelas
     */
    public function kirimWaOrtu(Request $request)
    {
        $tanggal = $request->input('tanggal', date('Y-m-d'));
        $res = \App\Services\WhatsAppService::sendDailyAlphaSummaryToParents($tanggal);

        $msg = "Notifikasi WhatsApp berhasil diproses! Total Siswa Alpa: {$res['total_siswa_alpa']} (Full: {$res['full_alpa']}, Partial: {$res['partial_alpa']}). Terkirim ke Ortu: {$res['ortu_sent']}, Terkirim ke Wali: {$res['wali_sent']}.";

        return back()->with('success', $msg);
    }

    /**
     * Rekapitulasi Guru Alpha (Bulanan & Matriks 12 Bulan) untuk Kepsek & Waka
     */
    public function guruAlpha(Request $request)
    {
        $tahun = (int)$request->input('tahun', date('Y'));
        $bulan = (int)$request->input('bulan', date('n')); // 1..12
        $tab = $request->input('tab', 'bulanan'); // 'bulanan' | 'matriks'
        $search = $request->input('search');

        $hariMap = [
            'Monday'    => 'Senin',
            'Tuesday'   => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday'  => 'Kamis',
            'Friday'    => 'Jumat',
            'Saturday'  => 'Sabtu',
            'Sunday'    => 'Minggu',
        ];

        $today = Carbon::today()->toDateString();

        // 1. Ambil Semua Data Guru
        $guruQuery = Guru::with(['jadwal.kelas', 'jadwal.mapel', 'jadwal.ruangan'])
            ->orderBy('nama_guru', 'asc');

        if (!empty($search)) {
            $guruQuery->where(function ($q) use ($search) {
                $q->where('nama_guru', 'like', "%{$search}%")
                  ->orWhere('nip', 'like', "%{$search}%");
            });
        }

        $allGuru = $guruQuery->get();

        // --- TAHAP 1: PERHITUNGAN REKAP BULANAN TERPILIH ---
        $startOfMonth = Carbon::createFromDate($tahun, $bulan, 1)->startOfMonth();
        $endOfMonth = Carbon::createFromDate($tahun, $bulan, 1)->endOfMonth();

        // Ambil semua jurnal pada bulan ini
        $jurnalsBulanIni = JurnalMengajar::whereBetween('tanggal', [$startOfMonth->toDateString(), $endOfMonth->toDateString()])
            ->get()
            ->groupBy(function ($item) {
                return $item->id_jadwal . '_' . $item->tanggal;
            });

        // Ambil semua izin guru yang disetujui pada bulan ini
        $izinsBulanIni = IzinGuru::where('status_akhir', 'Disetujui')
            ->whereDate('tanggal_mulai', '<=', $endOfMonth->toDateString())
            ->whereDate('tanggal_selesai', '>=', $startOfMonth->toDateString())
            ->get();

        // Kumpulkan hari kerja (Senin - Jumat) di bulan ini yang <= today
        $workDaysInMonth = [];
        $cursor = $startOfMonth->copy();
        while ($cursor->lte($endOfMonth)) {
            $dayNameEn = $cursor->format('l');
            if (isset($hariMap[$dayNameEn]) && in_array($hariMap[$dayNameEn], ['Senin','Selasa','Rabu','Kamis','Jumat'])) {
                $dateStr = $cursor->toDateString();
                $workDaysInMonth[] = [
                    'date'     => $dateStr,
                    'hari'     => $hariMap[$dayNameEn],
                    'is_past'  => ($dateStr <= $today),
                    'is_today' => ($dateStr === $today),
                ];
            }
            $cursor->addDay();
        }

        $rekapBulanan = $allGuru->map(function ($g) use ($workDaysInMonth, $jurnalsBulanIni, $izinsBulanIni) {
            $jadwalGuru = $g->jadwal ?? collect();
            $totalSesiWajib = 0;
            $totalHadir = 0;
            $totalIzin = 0;
            $totalAlpha = 0;
            $rincianAlpha = [];

            foreach ($workDaysInMonth as $wd) {
                if (!$wd['is_past']) continue; // Belum lewat, lewati

                // Jadwal guru di hari tersebut
                $jadwalHari = $jadwalGuru->where('hari', $wd['hari']);

                foreach ($jadwalHari as $j) {
                    $key = $j->id_jadwal . '_' . $wd['date'];
                    $jurnalAda = $jurnalsBulanIni->has($key);

                    // Cek event sekolah / pulang pagi — skip sesi ini dari perhitungan
                    $times  = $j->getWaktuMulaiSelesai();
                    $exempt = EventSekolah::cekExempt($wd['date'], $times['waktu_mulai'] ?? null);
                    if ($exempt['exempt'] && !$jurnalAda) {
                        continue; // Sesi ini tidak dihitung sama sekali
                    }

                    $totalSesiWajib++;

                    if ($jurnalAda) {
                        $totalHadir++;
                    } else {
                        // Cek Izin Sah
                        $hasIzin = $izinsBulanIni->where('id_guru', $g->id_guru)->first(function ($iz) use ($wd) {
                            return $iz->tanggal_mulai <= $wd['date'] && $iz->tanggal_selesai >= $wd['date'];
                        });

                        if ($hasIzin) {
                            $totalIzin++;
                        } else {
                            // Cek jika hari ini, apakah jamnya sudah telat
                            if ($wd['is_today']) {
                                $statusWaktu = $j->statusWaktuMengajar();
                                if ($statusWaktu === 'telat') {
                                    $totalAlpha++;
                                    $rincianAlpha[] = [
                                        'tanggal'   => $wd['date'],
                                        'hari'      => $wd['hari'],
                                        'jam_ke'    => "Jam ke-{$j->jam_mulai} s/d {$j->jam_selesai}",
                                        'kelas'     => $j->kelas ? $j->kelas->nama_kelas : '-',
                                        'mapel'     => $j->mapel ? $j->mapel->nama_mapel : '-',
                                        'ruangan'   => $j->ruangan ? $j->ruangan->nama_ruangan : '-',
                                    ];
                                }
                            } else {
                                $totalAlpha++;
                                $rincianAlpha[] = [
                                    'tanggal'   => $wd['date'],
                                    'hari'      => $wd['hari'],
                                    'jam_ke'    => "Jam ke-{$j->jam_mulai} s/d {$j->jam_selesai}",
                                    'kelas'     => $j->kelas ? $j->kelas->nama_kelas : '-',
                                    'mapel'     => $j->mapel ? $j->mapel->nama_mapel : '-',
                                    'ruangan'   => $j->ruangan ? $j->ruangan->nama_ruangan : '-',
                                ];
                            }
                        }
                    }
                }
            }

            $persenHadir = $totalSesiWajib > 0 ? round(($totalHadir / $totalSesiWajib) * 100, 2) : 100.00;
            $persenAlpha = $totalSesiWajib > 0 ? round(($totalAlpha / $totalSesiWajib) * 100, 2) : 0.00;

            // Status Tindak Lanjut
            $statusTindakLanjut = 'Tertib';
            $badgeColor = 'emerald';
            if ($totalAlpha >= 3) {
                $statusTindakLanjut = 'Peringatan / Tindak Lanjut';
                $badgeColor = 'rose';
            } elseif ($totalAlpha >= 1) {
                $statusTindakLanjut = 'Perlu Perhatian';
                $badgeColor = 'amber';
            }

            return [
                'id_guru'               => $g->id_guru,
                'nama_guru'             => $g->nama_guru,
                'nip'                   => $g->nip ?? '-',
                'no_hp'                 => $g->no_hp ?? '-',
                'total_jadwal_mingguan' => $jadwalGuru->count(),
                'total_sesi_wajib'      => $totalSesiWajib,
                'total_hadir'           => $totalHadir,
                'total_izin'            => $totalIzin,
                'total_alpha'           => $totalAlpha,
                'persen_hadir'          => $persenHadir,
                'persen_alpha'          => $persenAlpha,
                'status_tindak_lanjut'  => $statusTindakLanjut,
                'badge_color'           => $badgeColor,
                'rincian_alpha'         => $rincianAlpha,
            ];
        })->sortByDesc('total_alpha')->values();

        // --- TAHAP 2: PERHITUNGAN MATRIKS AKUMULASI 12 BULAN (JAN - DES) ---
        $startOfYear = Carbon::createFromDate($tahun, 1, 1)->startOfYear();
        $endOfYear = Carbon::createFromDate($tahun, 12, 31)->endOfYear();

        // Ambil semua jurnal & izin sepanjang tahun ini
        $jurnalsTahunIni = JurnalMengajar::whereBetween('tanggal', [$startOfYear->toDateString(), $endOfYear->toDateString()])
            ->get()
            ->groupBy(function ($item) {
                return $item->id_jadwal . '_' . $item->tanggal;
            });

        $izinsTahunIni = IzinGuru::where('status_akhir', 'Disetujui')
            ->whereDate('tanggal_mulai', '<=', $endOfYear->toDateString())
            ->whereDate('tanggal_selesai', '>=', $startOfYear->toDateString())
            ->get();

        // Kumpulkan hari kerja per bulan sepanjang tahun
        $monthsWorkDays = [];
        for ($m = 1; $m <= 12; $m++) {
            $mStart = Carbon::createFromDate($tahun, $m, 1)->startOfMonth();
            $mEnd = Carbon::createFromDate($tahun, $m, 1)->endOfMonth();
            $mDays = [];
            $mCur = $mStart->copy();
            while ($mCur->lte($mEnd)) {
                $dayNameEn = $mCur->format('l');
                if (isset($hariMap[$dayNameEn]) && in_array($hariMap[$dayNameEn], ['Senin','Selasa','Rabu','Kamis','Jumat'])) {
                    $dateStr = $mCur->toDateString();
                    $mDays[] = [
                        'date'     => $dateStr,
                        'hari'     => $hariMap[$dayNameEn],
                        'is_past'  => ($dateStr <= $today),
                        'is_today' => ($dateStr === $today),
                    ];
                }
                $mCur->addDay();
            }
            $monthsWorkDays[$m] = $mDays;
        }

        $namaBulanList = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        $rekapMatriks12Bulan = $allGuru->map(function ($g) use ($monthsWorkDays, $jurnalsTahunIni, $izinsTahunIni) {
            $jadwalGuru = $g->jadwal ?? collect();
            $alphaPerBulan = [];
            $totalAlphaTahunan = 0;
            $totalSesiWajibTahunan = 0;
            $totalHadirTahunan = 0;

            for ($m = 1; $m <= 12; $m++) {
                $mDays = $monthsWorkDays[$m] ?? [];
                $alphaBulanCount = 0;

                foreach ($mDays as $wd) {
                    if (!$wd['is_past']) continue;

                    $jadwalHari = $jadwalGuru->where('hari', $wd['hari']);
                    foreach ($jadwalHari as $j) {
                        $key = $j->id_jadwal . '_' . $wd['date'];
                        $jurnalAda = $jurnalsTahunIni->has($key);

                        // Skip sesi yang exempt karena event/pulang pagi
                        $times  = $j->getWaktuMulaiSelesai();
                        $exempt = EventSekolah::cekExempt($wd['date'], $times['waktu_mulai'] ?? null);
                        if ($exempt['exempt'] && !$jurnalAda) {
                            continue;
                        }

                        $totalSesiWajibTahunan++;

                        if ($jurnalAda) {
                            $totalHadirTahunan++;
                        } else {
                            $hasIzin = $izinsTahunIni->where('id_guru', $g->id_guru)->first(function ($iz) use ($wd) {
                                return $iz->tanggal_mulai <= $wd['date'] && $iz->tanggal_selesai >= $wd['date'];
                            });

                            if (!$hasIzin) {
                                if ($wd['is_today']) {
                                    if ($j->statusWaktuMengajar() === 'telat') {
                                        $alphaBulanCount++;
                                    }
                                } else {
                                    $alphaBulanCount++;
                                }
                            }
                        }
                    }
                }

                $alphaPerBulan[$m] = $alphaBulanCount;
                $totalAlphaTahunan += $alphaBulanCount;
            }

            // Status Tindak Lanjut Tahunan
            $statusTindakLanjut = 'Tertib';
            $badgeColor = 'emerald';
            if ($totalAlphaTahunan >= 5) {
                $statusTindakLanjut = 'Peringatan / Tindak Lanjut';
                $badgeColor = 'rose';
            } elseif ($totalAlphaTahunan >= 1) {
                $statusTindakLanjut = 'Perlu Perhatian';
                $badgeColor = 'amber';
            }

            return [
                'id_guru'               => $g->id_guru,
                'nama_guru'             => $g->nama_guru,
                'nip'                   => $g->nip ?? '-',
                'no_hp'                 => $g->no_hp ?? '-',
                'alpha_per_bulan'       => $alphaPerBulan,
                'total_alpha_tahunan'   => $totalAlphaTahunan,
                'total_sesi_tahunan'    => $totalSesiWajibTahunan,
                'total_hadir_tahunan'   => $totalHadirTahunan,
                'status_tindak_lanjut'  => $statusTindakLanjut,
                'badge_color'           => $badgeColor,
            ];
        })->sortByDesc('total_alpha_tahunan')->values();

        // KPI Ringkasan
        $kpi = [
            'total_guru'             => $allGuru->count(),
            'guru_alpha_bulan_ini'   => $rekapBulanan->where('total_alpha', '>', 0)->count(),
            'total_sesi_alpha_bulan' => $rekapBulanan->sum('total_alpha'),
            'guru_peringatan_bulan'  => $rekapBulanan->where('total_alpha', '>=', 3)->count(),
            'total_sesi_alpha_tahun' => $rekapMatriks12Bulan->sum('total_alpha_tahunan'),
            'guru_peringatan_tahun'  => $rekapMatriks12Bulan->where('total_alpha_tahunan', '>=', 5)->count(),
        ];

        return view('Admin.rekap.guru_alpha', compact(
            'tahun',
            'bulan',
            'tab',
            'search',
            'namaBulanList',
            'rekapBulanan',
            'rekapMatriks12Bulan',
            'kpi'
        ));
    }
}
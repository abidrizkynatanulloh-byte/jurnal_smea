<?php

namespace App\Http\Controllers\Guru;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Jadwal;
use App\Models\JurnalMengajar;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\JurnalDetailKetidakhadiran;
use App\Models\DispenSiswa;
use App\Models\IzinSiswa;
use App\Models\SiswaTelat;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

use App\Services\WhatsAppService;
use Illuminate\Support\Facades\Cache;

class GuruDashboardController extends Controller
{
    public function index()
    {
        $user  = Auth::user();
        $guru  = $user->guru;

        if (!$guru) {
            abort(403, 'Data guru tidak ditemukan.');
        }

        $hariMap = [
            'Monday'    => 'Senin',    'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',     'Thursday' => 'Kamis',
            'Friday'    => 'Jumat',    'Saturday' => 'Sabtu',
            'Sunday'    => 'Minggu',
        ];
        $namaHariIni  = $hariMap[Carbon::now()->format('l')] ?? 'Senin';
        $tanggalHariIni = Carbon::today()->toDateString();
        $tanggalTeks    = Carbon::now()->locale('id')->isoFormat('dddd, D MMMM Y');

        // Jadwal hari ini milik guru ini
        $jadwalHariIni = Jadwal::with(['kelas', 'mapel', 'ruangan', 'jamMulaiData', 'jamSelesaiData'])
            ->where('id_guru', $guru->id_guru)
            ->where('hari', $namaHariIni)
            ->orderBy('jam_mulai')
            ->get()
            ->map(function ($j) use ($tanggalHariIni) {
                $jurnal = JurnalMengajar::where('id_jadwal', $j->id_jadwal)
                    ->whereDate('tanggal', $tanggalHariIni)
                    ->first();
                $j->jurnal       = $jurnal;
                $j->sudah_diisi  = (bool) $jurnal;
                return $j;
            });

        // Deteksi Peringatan 5 Menit Sebelum Waktu Mengajar Habis & Kirim WA
        $peringatanJurnal = null;
        foreach ($jadwalHariIni as $j) {
            if (!$j->sudah_diisi && $j->statusWaktuMengajar() === 'sekarang') {
                $sisaMenit = $j->getSisaMenitSesi();
                if ($sisaMenit !== null && $sisaMenit <= 5 && $sisaMenit >= 0) {
                    $peringatanJurnal = [
                        'jadwal'     => $j,
                        'sisa_menit' => max(1, $sisaMenit),
                    ];

                    // Kirim pesan WhatsApp ke Guru (Hanya sekali per sesi hari ini via Cache)
                    $cacheKey = "wa_reminder_{$j->id_jadwal}_{$tanggalHariIni}";
                    if (!Cache::has($cacheKey)) {
                        WhatsAppService::sendReminderPengisianJurnal($j, max(1, $sisaMenit));
                        Cache::put($cacheKey, true, now()->addHours(6));
                    }
                    break;
                }
            }
        }

        // Total sesi & sudah diisi hari ini
        $totalSesiHariIni  = $jadwalHariIni->count();
        $sudahDiisiHariIni = $jadwalHariIni->where('sudah_diisi', true)->count();

        // Total jadwal seminggu
        $totalJadwalSemua = Jadwal::where('id_guru', $guru->id_guru)->count();

        // Sesi belum diisi minggu ini
        $belumIsiMingguIni = 0;
        $hariList = ['Senin','Selasa','Rabu','Kamis','Jumat'];
        $startMinggu = Carbon::now()->startOfWeek(Carbon::MONDAY)->toDateString();
        $endMinggu   = Carbon::now()->endOfWeek(Carbon::FRIDAY)->toDateString();

        $jadwalSeminggu = Jadwal::where('id_guru', $guru->id_guru)->get();
        foreach ($jadwalSeminggu as $j) {
            $dayIndex = array_search($j->hari, $hariList);
            if ($dayIndex === false) continue;
            $tanggalJadwal = Carbon::now()->startOfWeek(Carbon::MONDAY)->addDays($dayIndex)->toDateString();
            if ($tanggalJadwal > Carbon::today()->toDateString()) continue; // skip hari depan
            $ada = JurnalMengajar::where('id_jadwal', $j->id_jadwal)
                ->whereDate('tanggal', $tanggalJadwal)->exists();
            if (!$ada) $belumIsiMingguIni++;
        }

        return view('guru.dashboard', compact(
            'guru',
            'namaHariIni',
            'tanggalTeks',
            'jadwalHariIni',
            'totalSesiHariIni',
            'sudahDiisiHariIni',
            'totalJadwalSemua',
            'belumIsiMingguIni',
            'peringatanJurnal'
        ));
    }

    /**
     * Menu khusus Wali Kelas untuk memantau rekap absensi dan atensi siswa binaan.
     */
    public function waliKelas(Request $request)
    {
        $user = Auth::user();
        $guru = $user->guru;

        if (!$guru) {
            abort(403, 'Data guru tidak ditemukan.');
        }

        // 1. Ambil HANYA kelas di mana guru ini adalah wali kelasnya (Berdasarkan NIP / ID / Nama)
        $daftarKelas = Kelas::where(function($q) use ($guru) {
            $q->where('wali_kelas', $guru->nip);
            if ($guru->id_guru) {
                $q->orWhere('wali_kelas', $guru->id_guru);
            }
            if ($guru->nama_guru) {
                $q->orWhere('wali_kelas', $guru->nama_guru);
            }
        })->orderBy('nama_kelas')->get();

        // 2. Tolak akses jika guru ini BUKAN wali kelas dari kelas mana pun
        if ($daftarKelas->isEmpty()) {
            return redirect()->route('guru.dashboard')->withErrors(['error' => 'Akses ditolak. Anda tidak terdaftar sebagai Wali Kelas dari kelas manapun.']);
        }

        $kelasId = $request->query('kelas_id');

        if ($kelasId) {
            // 3. Pastikan kelas_id yang direquest memang milik wali kelas tersebut
            $kelasAktif = $daftarKelas->where('id_kelas', $kelasId)->first();
            
            // Jika memaksa memasukkan ID kelas lain via URL
            if (!$kelasAktif) {
                return redirect()->route('guru.wali-kelas')->withErrors(['error' => 'Akses ditolak. Anda bukan Wali Kelas dari kelas tersebut.']);
            }
        } else {
            // Default ke kelas pertama yang dipegang sebagai wali kelas
            $kelasAktif = $daftarKelas->first();
        }

        $rekapSiswa = collect();
        if ($kelasAktif) {
            $siswaList = Siswa::where('id_kelas', $kelasAktif->id_kelas)->orderBy('nama_siswa')->get();

            // Total pertemuan / jurnal kelas semester berjalan
            $totalJurnalKelas = JurnalMengajar::whereHas('jadwal', function($q) use ($kelasAktif) {
                $q->where('id_kelas', $kelasAktif->id_kelas);
            })->count();

            foreach ($siswaList as $s) {
                // 1. Ambil Izin Resmi Siswa yang sudah Disetujui Piket (IzinSiswa)
                $semuaIzinResmi = IzinSiswa::where('nis', $s->nis)
                    ->where('status', 'Disetujui')
                    ->get();

                $tanggalIzinSakitResmi = [];
                $sakitCount = 0;
                $izinCount = 0;

                foreach ($semuaIzinResmi as $ir) {
                    $period = CarbonPeriod::create($ir->tanggal_mulai, $ir->tanggal_selesai);
                    foreach ($period as $dt) {
                        $tglStr = $dt->toDateString();
                        $kat = ucfirst(strtolower($ir->kategori));
                        if (!isset($tanggalIzinSakitResmi[$tglStr])) {
                            $tanggalIzinSakitResmi[$tglStr] = [
                                'kategori' => $kat,
                                'alasan'   => $ir->alasan ?: 'Izin resmi disetujui Guru Piket',
                                'sumber'   => 'Guru Piket'
                            ];
                            if ($kat === 'Sakit') $sakitCount++;
                            elseif ($kat === 'Izin') $izinCount++;
                        }
                    }
                }

                // 2. Ambil Ketidakhadiran dari Jurnal Mengajar Guru Mapel
                $semuaKetidakhadiran = JurnalDetailKetidakhadiran::with(['jurnal.jadwal.jamMulaiData', 'jurnal.jadwal.jamSelesaiData'])
                    ->where('id_siswa', $s->nis)
                    ->get();

                $alpaCount = 0;
                $telatJurnalCount = 0;
                $groupedByDate = [];

                foreach ($semuaKetidakhadiran as $kh) {
                    if (!$kh->jurnal) continue;
                    $tgl = $kh->jurnal->tanggal;
                    $jamM = $kh->jurnal->jadwal->jamMulaiData->jam_ke ?? '?';
                    $jamS = $kh->jurnal->jadwal->jamSelesaiData->jam_ke ?? '?';
                    $teksJam = $jamM == $jamS ? "Jam ke-$jamM" : "Jam ke-$jamM-$jamS";

                    if ($kh->keterangan == 'Alpa') {
                        $alpaCount++;
                    } elseif ($kh->keterangan == 'Terlambat') {
                        $telatJurnalCount++;
                    } elseif ($kh->keterangan == 'Sakit' || $kh->keterangan == 'Izin') {
                        if (!isset($tanggalIzinSakitResmi[$tgl])) {
                            if ($kh->keterangan == 'Sakit') $sakitCount++;
                            elseif ($kh->keterangan == 'Izin') $izinCount++;
                        }
                    }

                    if (!isset($groupedByDate[$tgl])) $groupedByDate[$tgl] = [];
                    if (!isset($groupedByDate[$tgl][$kh->keterangan])) $groupedByDate[$tgl][$kh->keterangan] = [];
                    $groupedByDate[$tgl][$kh->keterangan][] = $teksJam;
                }

                // 3. Susun Riwayat Absen untuk Modal
                $riwayatAbsen = [];
                foreach ($tanggalIzinSakitResmi as $tglStr => $infoIzin) {
                    $detailJam = "1 Hari Full (Izin Resmi Piket)";
                    if (isset($groupedByDate[$tglStr][$infoIzin['kategori']])) {
                        $jams = $groupedByDate[$tglStr][$infoIzin['kategori']];
                        $detailJam .= " • Sesi " . implode(', ', $jams);
                        unset($groupedByDate[$tglStr][$infoIzin['kategori']]);
                    }
                    $riwayatAbsen[] = [
                        'tanggal'    => $tglStr,
                        'keterangan' => $infoIzin['kategori'],
                        'detail_jam' => $detailJam,
                        'alasan'     => $infoIzin['alasan'],
                        'sumber'     => $infoIzin['sumber']
                    ];
                }

                foreach ($groupedByDate as $tgl => $ketGroups) {
                    foreach ($ketGroups as $ket => $jams) {
                        $jamText = implode(', ', $jams);
                        if (count($jams) >= 4) {
                            $jamText = "1 Hari Full (" . count($jams) . " Sesi)";
                        } else {
                            $jamText = "Di " . $jamText;
                        }
                        $riwayatAbsen[] = [
                            'tanggal'    => $tgl,
                            'keterangan' => $ket,
                            'detail_jam' => $jamText,
                            'alasan'     => '-',
                            'sumber'     => 'Jurnal Kelas'
                        ];
                    }
                }

                usort($riwayatAbsen, function($a, $b) {
                    return strtotime($b['tanggal']) - strtotime($a['tanggal']);
                });

                // Riwayat Dispensasi
                $semuaDispen = DispenSiswa::where('nis', $s->nis)
                    ->orderBy('tanggal', 'desc')
                    ->get();

                $dispenCount = 0;
                $riwayatDispen = [];
                foreach ($semuaDispen as $d) {
                    $tglMulai = $d->tanggal;
                    $tglSelesai = $d->tanggal_selesai ?: $d->tanggal;

                    if ($d->status === 'Disetujui' || $d->status === 'Sudah Kembali' || $d->status === 'Sedang di Luar') {
                        $period = CarbonPeriod::create($tglMulai, $tglSelesai);
                        $dispenCount += count($period);
                    }

                    $periodeTeks = ($d->tanggal_selesai && $d->tanggal_selesai !== $d->tanggal)
                        ? \Carbon\Carbon::parse($d->tanggal)->translatedFormat('d M Y') . ' s/d ' . \Carbon\Carbon::parse($d->tanggal_selesai)->translatedFormat('d M Y')
                        : \Carbon\Carbon::parse($d->tanggal)->locale('id')->isoFormat('dddd, D MMMM Y');

                    $riwayatDispen[] = [
                        'tanggal'         => $d->tanggal,
                        'tanggal_selesai' => $d->tanggal_selesai,
                        'periode_teks'    => $periodeTeks,
                        'keperluan'       => $d->keperluan,
                        'jam_ke'          => $d->jam_ke,
                        'status'          => $d->status,
                        'jam_keluar'      => $d->jam_keluar_aktual,
                        'jam_kembali'     => $d->jam_kembali_aktual,
                    ];
                }

                // Riwayat Keterlambatan (Piket & Jurnal)
                $semuaTelatPiket = SiswaTelat::where('nis', $s->nis)
                    ->orderBy('tanggal', 'desc')
                    ->get();

                $riwayatTelat = [];
                foreach ($semuaTelatPiket as $tp) {
                    $riwayatTelat[] = [
                        'tanggal'       => $tp->tanggal,
                        'jam_terlambat' => $tp->jam_terlambat ? substr($tp->jam_terlambat, 0, 5) . ' WIB' : '-',
                        'alasan'        => $tp->alasan ?: 'Tidak ada catatan alasan',
                        'tindakan'      => $tp->tindakan ?: '-',
                        'sumber'        => 'Guru Piket'
                    ];
                }

                $telatJurnals = $semuaKetidakhadiran->where('keterangan', 'Terlambat');
                foreach ($telatJurnals as $tj) {
                    if (!$tj->jurnal) continue;
                    $jamM = $tj->jurnal->jadwal->jamMulaiData->jam_ke ?? '?';
                    $jamS = $tj->jurnal->jadwal->jamSelesaiData->jam_ke ?? '?';
                    $teksJam = $jamM == $jamS ? "Jam ke-$jamM" : "Jam ke-$jamM-$jamS";
                    
                    // Cek jika sudah tercatat di tanggal yang sama oleh piket
                    $tglJurnal = $tj->jurnal->tanggal;
                    $riwayatTelat[] = [
                        'tanggal'       => $tglJurnal,
                        'jam_terlambat' => $teksJam,
                        'alasan'        => $tj->jurnal->materi ? "Materi: " . \Illuminate\Support\Str::limit($tj->jurnal->materi, 35) : 'Keterlambatan di sesi mapel',
                        'tindakan'      => '-',
                        'sumber'        => 'Jurnal Kelas'
                    ];
                }

                usort($riwayatTelat, function($a, $b) {
                    return strtotime($b['tanggal']) - strtotime($a['tanggal']);
                });

                $telatCount = count($riwayatTelat);

                // 1. Ambil daftar tanggal (unik) di mana siswa dicatat 'Alpa'
                $tanggalAlpaList = JurnalDetailKetidakhadiran::where('id_siswa', $s->nis)
                    ->where('keterangan', 'Alpa')
                    ->whereHas('jurnal')
                    ->get()
                    ->map(function ($kh) {
                        return $kh->jurnal->tanggal;
                    })
                    ->unique()
                    ->sort()
                    ->values();

                $maxBerturut = 0;
                $currentBerturut = 0;
                $prevDate = null;

                foreach ($tanggalAlpaList as $tgl) {
                    $currDate = \Carbon\Carbon::parse($tgl);

                    if ($prevDate === null) {
                        $currentBerturut = 1;
                    } else {
                        $diff = $prevDate->diffInDays($currDate);

                        // Selisih 1 hari (atau 3 hari jika melewati weekend Jumat -> Senin, atau 2 hari Sabtu -> Senin)
                        if ($diff == 1 || ($prevDate->isFriday() && $diff == 3) || ($prevDate->isSaturday() && $diff == 2)) {
                            $currentBerturut++;
                        } else {
                            $currentBerturut = 1;
                        }
                    }

                    if ($currentBerturut > $maxBerturut) {
                        $maxBerturut = $currentBerturut;
                    }

                    $prevDate = $currDate;
                }

                // 2. Kriteria Status Peringatan Siswa (Sesuai Aturan Sekolah):
                // - Alpa 3 hari berturut-turut
                // - ATAU Total Alpa >= 7 hari (meski tidak berturut-turut)
                $alpaBerturut3Hari   = $maxBerturut >= 3;
                $alpaTotal7Hari      = $alpaCount >= 7;
                $perluPerhatianAlpa  = $alpaBerturut3Hari || $alpaTotal7Hari;
                $perluBimbinganTelat = $telatCount >= 3;   // Total Telat >= 3 Kali -> SERING TELAT

                // 3. Perhitungan Persentase
                // Siswa yang telat TETAP DIHITUNG HADIR
                $totalTidakHadir = $alpaCount + $sakitCount + $izinCount;
                $totalHadir = max(0, $totalJurnalKelas - $totalTidakHadir);
                $persentaseTelat = $totalJurnalKelas > 0 ? round(($telatCount / $totalJurnalKelas) * 100, 1) : 0;
                $persentaseHadir = $totalJurnalKelas > 0 ? round(($totalHadir / $totalJurnalKelas) * 100, 1) : 100;

                // 4. Simpan ke array rekapSiswa
                $rekapSiswa->push([
                    'nis'                   => $s->nis,
                    'nama_siswa'            => $s->nama_siswa,
                    'alpa'                  => $alpaCount,
                    'sakit'                 => $sakitCount,
                    'izin'                  => $izinCount,
                    'telat'                 => $telatCount,
                    'dispen'                => $dispenCount,
                    'total_absen'           => $totalTidakHadir,
                    'total_hadir'           => $totalHadir,
                    'persentase_telat'      => $persentaseTelat,
                    'persentase_hadir'      => $persentaseHadir,
                    'alpa_berturut_3'       => $alpaBerturut3Hari,
                    'alpa_total_7'          => $alpaTotal7Hari,
                    'perlu_perhatian_alpa'  => $perluPerhatianAlpa,
                    'perlu_bimbingan_telat' => $perluBimbinganTelat,
                    'max_berturut_alpa'     => $maxBerturut,
                    'riwayat_absen'         => $riwayatAbsen,
                    'riwayat_dispen'        => $riwayatDispen,
                    'riwayat_telat'         => $riwayatTelat,
                ]);
            }
        }

        return view('guru.wali-kelas.index', compact('guru', 'daftarKelas', 'kelasAktif', 'rekapSiswa'));
    }
}
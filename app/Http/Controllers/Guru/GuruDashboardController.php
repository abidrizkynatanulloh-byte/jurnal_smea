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
use Carbon\Carbon;

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

            foreach ($siswaList as $s) {
                $semuaKetidakhadiran = JurnalDetailKetidakhadiran::with(['jurnal.jadwal.jamMulaiData', 'jurnal.jadwal.jamSelesaiData'])
                    ->where('id_siswa', $s->nis)
                    ->get();

                $alpaCount = 0; $sakitCount = 0; $izinCount = 0;
                $groupedByDate = [];
                
                foreach ($semuaKetidakhadiran as $kh) {
                    if ($kh->keterangan == 'Alpa') $alpaCount++;
                    elseif ($kh->keterangan == 'Sakit') $sakitCount++;
                    elseif ($kh->keterangan == 'Izin') $izinCount++;

                    if (!$kh->jurnal) continue;
                    $tgl = $kh->jurnal->tanggal;
                    $jamM = $kh->jurnal->jadwal->jamMulaiData->jam_ke ?? '?';
                    $jamS = $kh->jurnal->jadwal->jamSelesaiData->jam_ke ?? '?';
                    $teksJam = $jamM == $jamS ? "Jam ke-$jamM" : "Jam ke-$jamM-$jamS";
                    
                    if (!isset($groupedByDate[$tgl])) $groupedByDate[$tgl] = [];
                    if (!isset($groupedByDate[$tgl][$kh->keterangan])) $groupedByDate[$tgl][$kh->keterangan] = [];
                    $groupedByDate[$tgl][$kh->keterangan][] = $teksJam;
                }

                $riwayatAbsen = [];
                foreach ($groupedByDate as $tgl => $ketGroups) {
                    foreach ($ketGroups as $ket => $jams) {
                        $jamText = implode(', ', $jams);
                        if (count($jams) >= 4) {
                            $jamText = "1 Hari Full (" . count($jams) . " Sesi)";
                        } else {
                            $jamText = "Di " . $jamText;
                        }
                        $riwayatAbsen[] = [
                            'tanggal' => $tgl,
                            'keterangan' => $ket,
                            'detail_jam' => $jamText
                        ];
                    }
                }
                usort($riwayatAbsen, function($a, $b) {
                    return strtotime($b['tanggal']) - strtotime($a['tanggal']);
                });

                $semuaDispen = DispenSiswa::where('nis', $s->nis)
                    ->orderBy('tanggal', 'desc')
                    ->get();

                $dispenCount = $semuaDispen->count();

                $riwayatDispen = [];
                foreach ($semuaDispen as $d) {
                    $riwayatDispen[] = [
                        'tanggal'    => $d->tanggal,
                        'keperluan'  => $d->keperluan,
                        'jam_ke'     => $d->jam_ke,
                        'status'     => $d->status,
                        'jam_keluar' => $d->jam_keluar_aktual,
                        'jam_kembali'=> $d->jam_kembali_aktual,
                    ];
                }

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

                        // Selisih 1 hari (atau 3 hari jika melewati weekend Jumat -> Senin)
                        if ($diff == 1 || ($prevDate->isFriday() && $diff == 3)) {
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

                // 2. Kriteria 3 Status Peringatan:
                $perluPengawasan = $maxBerturut >= 5; // 5 Hari Berturut-turut -> TERAWASI
                $perluTindak     = $alpaCount > 5;     // Total Alpa > 5 Hari (Acak) -> PERLU DITINDAK
                $perluAtensi     = $alpaCount >= 3;    // Total Alpa >= 3 Hari -> PERLU ATENSI

                // 3. Simpan ke array rekapSiswa
                $rekapSiswa->push([
                    'nis'               => $s->nis,
                    'nama_siswa'        => $s->nama_siswa,
                    'alpa'              => $alpaCount,
                    'sakit'             => $sakitCount,
                    'izin'              => $izinCount,
                    'dispen'            => $dispenCount,
                    'total_absen'       => $alpaCount + $sakitCount + $izinCount,
                    'perlu_atensi'      => $perluAtensi,
                    'perlu_pengawasan'  => $perluPengawasan,
                    'perlu_tindak'      => $perluTindak,
                    'max_berturut_alpa' => $maxBerturut,
                    'riwayat_absen'     => $riwayatAbsen,
                    'riwayat_dispen'    => $riwayatDispen,
                ]);
            }
            $rekapSiswa = $rekapSiswa->sortByDesc('total_absen')->values();
        }

        return view('guru.wali-kelas.index', compact('guru', 'daftarKelas', 'kelasAktif', 'rekapSiswa'));
    }
}
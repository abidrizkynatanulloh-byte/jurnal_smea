<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Siswa;
use App\Models\Jadwal;
use App\Models\JurnalMengajar;
use App\Models\JurnalDetailKetidakhadiran;
use App\Models\DispenSiswa;
use App\Models\IzinSiswa;
use App\Models\SiswaTelat;
use App\Services\WhatsAppService;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class OrtuController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Ambil data siswa yang terhubung dengan akun orang tua ini
        $siswa = null;
        if ($user && $user->nisn_siswa) {
            $siswa = Siswa::with('kelas')->where('nisn', $user->nisn_siswa)->first();
        }

        if (!$siswa && $user && $user->username) {
            $siswa = Siswa::with('kelas')->where('nisn', $user->username)->orWhere('nis', $user->username)->first();
        }

        // Fallback untuk testing jika relasi nisn_siswa belum di-set pada akun login
        if (!$siswa) {
            $siswa = Siswa::with('kelas')->first();
        }

        $hariIni = Carbon::today()->toDateString();
        $hariMap = [
            'Monday'    => 'Senin',
            'Tuesday'   => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday'  => 'Kamis',
            'Friday'    => 'Jumat',
            'Saturday'  => 'Sabtu',
            'Sunday'    => 'Minggu',
        ];
        $namaHari = $hariMap[Carbon::now()->format('l')] ?? 'Senin';

        $presensiPerJp = collect();
        $dispenHariIni = null;
        $izinHariIni   = null;
        $rekapBulanIni = [
            'hadir' => 0,
            'sakit' => 0,
            'izin'  => 0,
            'alpa'  => 0,
            'telat' => 0,
        ];

        $awalBulan = Carbon::now()->startOfMonth()->toDateString();
        $akhirBulan = Carbon::now()->endOfMonth()->toDateString();

        if ($siswa && $siswa->id_kelas) {
            // 1. Cek Izin Resmi Siswa Hari Ini (yang sudah di-ACC Piket)
            $izinHariIni = IzinSiswa::where('nis', $siswa->nis)
                ->where('status', 'Disetujui')
                ->where('tanggal_mulai', '<=', $hariIni)
                ->where('tanggal_selesai', '>=', $hariIni)
                ->latest()
                ->first();

            // 2. Jadwal kelas anak hari ini
            $jadwalHariIni = Jadwal::with(['mapel', 'guru', 'ruangan', 'jamMulaiData', 'jamSelesaiData'])
                ->where('id_kelas', $siswa->id_kelas)
                ->where('hari', $namaHari)
                ->orderBy('jam_mulai')
                ->get();

            // 3. Cek status presensi per jam pelajaran hari ini
            foreach ($jadwalHariIni as $j) {
                $jurnal = JurnalMengajar::where('id_jadwal', $j->id_jadwal)
                    ->where('tanggal', $hariIni)
                    ->first();

                $waktuJam = '';
                if ($j->jamMulaiData && $j->jamSelesaiData) {
                    $wm = substr($j->jamMulaiData->waktu_mulai, 0, 5);
                    $ws = substr($j->jamSelesaiData->waktu_selesai, 0, 5);
                    $waktuJam = " ({$wm} - {$ws} WIB)";
                }

                $statusKehadiran = 'Belum Dimulai';
                $badgeClass = 'bg-slate-100 text-slate-500';

                if ($jurnal) {
                    $tidakHadir = JurnalDetailKetidakhadiran::where('id_jurnal', $jurnal->id_jurnal)
                        ->where('id_siswa', $siswa->nis)
                        ->first();

                    if ($tidakHadir) {
                        if ($tidakHadir->keterangan === 'Terlambat') {
                            $statusKehadiran = 'Hadir (Terlambat)';
                            $badgeClass = 'bg-amber-50 text-amber-700 border border-amber-200';
                        } else {
                            $statusKehadiran = $tidakHadir->keterangan; // Sakit, Izin, atau Alpa
                            $badgeClass = match ($tidakHadir->keterangan) {
                                'Sakit' => 'bg-blue-50 text-blue-700 border border-blue-200',
                                'Izin'  => 'bg-purple-50 text-purple-700 border border-purple-200',
                                default => 'bg-rose-50 text-rose-700 border border-rose-200',
                            };
                        }
                    } else {
                        $statusKehadiran = 'Hadir';
                        $badgeClass = 'bg-emerald-50 text-emerald-700 border border-emerald-200';
                    }
                } elseif ($izinHariIni) {
                    // Jika jurnal belum diisi tapi sudah ada izin resmi piket
                    if ($izinHariIni->kategori === 'Sakit') {
                        $statusKehadiran = 'Izin Sakit (Disetujui)';
                        $badgeClass = 'bg-blue-50 text-blue-700 border border-blue-200';
                    } elseif ($izinHariIni->kategori === 'Izin') {
                        $statusKehadiran = 'Izin Resmi (Disetujui)';
                        $badgeClass = 'bg-purple-50 text-purple-700 border border-purple-200';
                    }
                }

                $presensiPerJp->push([
                    'jam_ke'    => $j->jam_mulai == $j->jam_selesai ? "Jam {$j->jam_mulai}" : "Jam {$j->jam_mulai}–{$j->jam_selesai}",
                    'waktu_jam' => $waktuJam,
                    'mapel'     => $j->mapel ? $j->mapel->nama_mapel : 'Pelajaran',
                    'guru'      => $j->guru ? $j->guru->nama_guru : '-',
                    'ruangan'   => $j->ruangan ? $j->ruangan->nama_ruangan : '-',
                    'status'    => $statusKehadiran,
                    'badge'     => $badgeClass,
                ]);
            }

            // 4. Status dispensasi keluar anak hari ini
            $dispenHariIni = DispenSiswa::where('nis', $siswa->nis)
                ->whereDate('tanggal', '<=', $hariIni)
                ->where(function ($q) use ($hariIni) {
                    $q->whereNull('tanggal_selesai')
                      ->whereDate('tanggal', $hariIni)
                      ->orWhereDate('tanggal_selesai', '>=', $hariIni);
                })
                ->whereIn('status', ['Disetujui', 'Sedang di Luar'])
                ->latest()
                ->first();

            // 5. Rekap Izin / Sakit Resmi dari Piket Bulan Ini
            $semuaIzinResmiBulanIni = IzinSiswa::where('nis', $siswa->nis)
                ->where('status', 'Disetujui')
                ->where(function($q) use ($awalBulan, $akhirBulan) {
                    $q->whereBetween('tanggal_mulai', [$awalBulan, $akhirBulan])
                      ->orWhereBetween('tanggal_selesai', [$awalBulan, $akhirBulan])
                      ->orWhere(function($sub) use ($awalBulan, $akhirBulan) {
                          $sub->where('tanggal_mulai', '<=', $awalBulan)
                              ->where('tanggal_selesai', '>=', $akhirBulan);
                      });
                })
                ->get();

            $tanggalIzinSakitResmi = [];
            $tanggalSakitBulanIni = [];
            $tanggalIzinBulanIni  = [];

            foreach ($semuaIzinResmiBulanIni as $ir) {
                $period = CarbonPeriod::create(max($awalBulan, $ir->tanggal_mulai), min($akhirBulan, $ir->tanggal_selesai));
                foreach ($period as $dt) {
                    $dayNameEn = $dt->format('l');
                    if (in_array($dayNameEn, ['Saturday', 'Sunday'])) continue;
                    $tglStr = $dt->toDateString();
                    $kat = ucfirst(strtolower($ir->kategori));

                    if (!isset($tanggalIzinSakitResmi[$tglStr])) {
                        $tanggalIzinSakitResmi[$tglStr] = [
                            'kategori' => $kat,
                            'alasan'   => $ir->alasan ?: 'Izin resmi disetujui Guru Piket',
                            'sumber'   => 'Guru Piket',
                        ];
                        if ($kat === 'Sakit') $tanggalSakitBulanIni[$tglStr] = true;
                        elseif ($kat === 'Izin') $tanggalIzinBulanIni[$tglStr] = true;
                    }
                }
            }

            // 6. Rekap ketidakhadiran dari Jurnal Mengajar bulan ini (per hari)
            $ketidakhadiranList = JurnalDetailKetidakhadiran::with('jurnal')
                ->where('id_siswa', $siswa->nis)
                ->whereHas('jurnal', function ($q) use ($awalBulan, $akhirBulan) {
                    $q->whereBetween('tanggal', [$awalBulan, $akhirBulan]);
                })
                ->get();

            $tanggalAlpaBulanIni = [];
            foreach ($ketidakhadiranList as $kh) {
                $tglJurnal = $kh->jurnal ? $kh->jurnal->tanggal : null;
                if (!$tglJurnal) continue;

                if ($kh->keterangan === 'Alpa') {
                    $tanggalAlpaBulanIni[$tglJurnal] = true;
                } elseif ($kh->keterangan === 'Terlambat') {
                    $rekapBulanIni['telat']++;
                } elseif ($kh->keterangan === 'Sakit') {
                    $tanggalSakitBulanIni[$tglJurnal] = true;
                } elseif ($kh->keterangan === 'Izin') {
                    $tanggalIzinBulanIni[$tglJurnal] = true;
                }
            }

            $rekapBulanIni['sakit'] = count($tanggalSakitBulanIni);
            $rekapBulanIni['izin']  = count($tanggalIzinBulanIni);
            $rekapBulanIni['alpa']  = count($tanggalAlpaBulanIni);

            // Ambil juga catatan telat dari piket bulan ini
            $piketTelatBulanIni = SiswaTelat::where('nis', $siswa->nis)
                ->whereBetween('tanggal', [$awalBulan, $akhirBulan])
                ->count();
            if ($piketTelatBulanIni > $rekapBulanIni['telat']) {
                $rekapBulanIni['telat'] = $piketTelatBulanIni;
            }

            // Total jurnal mengajar di kelas anak bulan ini
            $totalJurnalKelas = JurnalMengajar::whereHas('jadwal', function($q) use ($siswa) {
                $q->where('id_kelas', $siswa->id_kelas);
            })->whereBetween('tanggal', [$awalBulan, $akhirBulan])->count();

            // Siswa terlambat tetap terhitung HADIR
            $totalTidakHadir = $rekapBulanIni['sakit'] + $rekapBulanIni['izin'] + $rekapBulanIni['alpa'];
            $rekapBulanIni['hadir'] = max(0, $totalJurnalKelas - $totalTidakHadir);
        }

        // 7. Riwayat Izin yang diajukan Orang Tua
        $riwayatIzin = [];
        if ($siswa) {
            $riwayatIzin = IzinSiswa::where('nis', $siswa->nis)
                ->orderBy('created_at', 'desc')
                ->get();
        }

        // 8. Riwayat Ketidakhadiran & Keterlambatan (Semua Waktu)
        $riwayatAbsen = [];
        $riwayatTelat = [];
        if ($siswa) {
            // Ambil semua izin resmi piket siswa sepanjang waktu
            $semuaIzinResmiTotal = IzinSiswa::where('nis', $siswa->nis)
                ->where('status', 'Disetujui')
                ->get();

            $tanggalIzinSakitTotal = [];
            foreach ($semuaIzinResmiTotal as $ir) {
                $period = CarbonPeriod::create($ir->tanggal_mulai, $ir->tanggal_selesai);
                foreach ($period as $dt) {
                    $tglStr = $dt->toDateString();
                    $kat = ucfirst(strtolower($ir->kategori));
                    if (!isset($tanggalIzinSakitTotal[$tglStr])) {
                        $tanggalIzinSakitTotal[$tglStr] = [
                            'kategori' => $kat,
                            'alasan'   => $ir->alasan ?: 'Izin resmi disetujui Guru Piket',
                            'sumber'   => 'Guru Piket'
                        ];
                    }
                }
            }

            $semuaKetidakhadiran = JurnalDetailKetidakhadiran::with(['jurnal.jadwal.jamMulaiData', 'jurnal.jadwal.jamSelesaiData'])
                ->where('id_siswa', $siswa->nis)
                ->get();
                
            $groupedByDate = [];
            foreach ($semuaKetidakhadiran as $kh) {
                if (!$kh->jurnal) continue;
                $tgl = $kh->jurnal->tanggal;
                $keterangan = $kh->keterangan;
                $jamM = $kh->jurnal->jadwal->jamMulaiData->jam_ke ?? '?';
                $jamS = $kh->jurnal->jadwal->jamSelesaiData->jam_ke ?? '?';
                $teksJam = $jamM == $jamS ? "Jam ke-$jamM" : "Jam ke-$jamM-$jamS";
                
                if (!isset($groupedByDate[$tgl])) {
                    $groupedByDate[$tgl] = [];
                }
                if (!isset($groupedByDate[$tgl][$keterangan])) {
                    $groupedByDate[$tgl][$keterangan] = [];
                }
                $groupedByDate[$tgl][$keterangan][] = $teksJam;
            }
            
            // Masukkan data Izin Resmi Piket
            foreach ($tanggalIzinSakitTotal as $tglStr => $infoIzin) {
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

            // Masukkan data dari Jurnal Mapel
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

            // Ambil catatan riwayat telat dari pos piket
            $riwayatTelat = SiswaTelat::where('nis', $siswa->nis)
                ->orderBy('tanggal', 'desc')
                ->get();

            usort($riwayatAbsen, function($a, $b) {
                return strtotime($b['tanggal']) - strtotime($a['tanggal']);
            });
        }

        return view('ortu.dashboard', compact(
            'siswa',
            'hariIni',
            'namaHari',
            'presensiPerJp',
            'dispenHariIni',
            'izinHariIni',
            'rekapBulanIni',
            'riwayatIzin',
            'riwayatAbsen',
            'riwayatTelat'
        ));
    }

    /**
     * Memproses Pengajuan Izin / Sakit dari Orang Tua (Wajib Melampirkan Foto/Surat Dokter)
     */
    public function storeIzin(Request $request)
    {
        $request->validate([
            'kategori'        => 'required|in:Sakit,Izin,Dispen,Lainnya',
            'alasan'          => 'required|string|max:1000',
            'tanggal_mulai'   => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'bukti_foto'      => 'required|image|mimes:jpeg,jpg,png,webp|max:5120',
        ], [
            'kategori.required'        => 'Kategori izin wajib dipilih.',
            'alasan.required'          => 'Alasan ketidakhadiran wajib diisi.',
            'tanggal_mulai.required'   => 'Tanggal mulai wajib diisi.',
            'tanggal_selesai.required' => 'Tanggal selesai wajib diisi.',
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai tidak boleh kurang dari tanggal mulai.',
            'bukti_foto.required'      => 'Bukti foto / surat dokter wajib diunggah.',
            'bukti_foto.image'         => 'File bukti harus berupa gambar (JPG, PNG, WEBP).',
            'bukti_foto.max'           => 'Ukuran foto maksimal 5 MB.',
        ]);

        $user = Auth::user();
        $siswa = null;
        if ($user && $user->nisn_siswa) {
            $siswa = Siswa::where('nisn', $user->nisn_siswa)->first();
        }
        if (!$siswa && $user && $user->username) {
            $siswa = Siswa::where('nisn', $user->username)->orWhere('nis', $user->username)->first();
        }
        if (!$siswa) {
            $siswa = Siswa::first();
        }

        $fotoPath = null;
        if ($request->hasFile('bukti_foto')) {
            $file = $request->file('bukti_foto');
            $filename = 'bukti_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $destinationPath = public_path('uploads/bukti_izin');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }
            $file->move($destinationPath, $filename);
            $fotoPath = 'uploads/bukti_izin/' . $filename;
        }

        $izin = IzinSiswa::create([
            'nis'             => $siswa->nis,
            'kategori'        => $request->kategori,
            'alasan'          => $request->alasan,
            'tanggal_mulai'   => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'bukti_foto'      => $fotoPath,
            'status'          => 'Pending',
        ]);

        // Kirim notifikasi WA ke Guru Piket & Waka Kesiswaan
        WhatsAppService::sendIzinSiswaNotification($izin);

        return redirect()->back()->with('success', 'Pengajuan izin siswa berhasil dikirim. Menunggu verifikasi dari Guru Piket / Wali Kelas.');
    }
}

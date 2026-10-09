<?php

namespace App\Http\Controllers\Guru;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Jadwal;
use App\Models\JurnalMengajar;
use App\Models\JurnalDetailKetidakhadiran;
use App\Models\Siswa;
use App\Models\IzinSiswa;
use App\Models\PengajuanIzinSiswa;
use App\Models\DispenSiswa;
use App\Models\SiswaTelat;
use App\Models\FotoMengajar;
use App\Models\EventSekolah;
use App\Services\WhatsAppService;
use Carbon\Carbon;

class JurnalController
{
    /**
     * Form input jurnal untuk 1 sesi jadwal.
     */
    public function create($id_jadwal)
    {
        $user = Auth::user();
        $guru = $user->guru;

        if (!$guru) {
            abort(403, 'Data guru tidak ditemukan.');
        }

        // Pastikan jadwal ini milik guru yang login
        $jadwal = Jadwal::with(['kelas', 'mapel', 'ruangan'])
            ->where('id_jadwal', $id_jadwal)
            ->where('id_guru', $guru->id_guru)
            ->firstOrFail();

        // Cek status waktu saat ini
        $statusWaktu = $jadwal->statusWaktuMengajar();

        if ($statusWaktu === 'belum') {
            return redirect()->route('guru.dashboard')
                ->withErrors(['error' => 'Belum waktunya mengajar. Jurnal baru bisa diisi saat jam pelajaran dimulai.']);
        }

        if ($statusWaktu === 'telat') {
            return redirect()->route('guru.dashboard')
                ->withErrors(['error' => 'Waktu pengisian jurnal telah habis! Sesi mengajar ini sudah tercatat Terlambat (Alpa) dan tidak dapat diisi lagi.']);
        }

        $tanggalHariIni = Carbon::today()->toDateString();

        // Cek apakah jurnal hari ini sudah ada
        $jurnalAda = JurnalMengajar::where('id_jadwal', $id_jadwal)
            ->whereDate('tanggal', $tanggalHariIni)
            ->first();

        if ($jurnalAda) {
            return redirect()->route('guru.jurnal.show', $jurnalAda->id_jurnal)
                ->with('info', 'Jurnal untuk sesi ini sudah pernah diisi.');
        }

        // Ambil daftar siswa di kelas ini + cek status otomatis (Poin 6: Otomatis Sakit/Izin/Dispen jika jam sebelumnya sudah izin)
        $siswaDiKelas = Siswa::where('id_kelas', $jadwal->id_kelas)
            ->orderBy('nama_siswa')
            ->get()
            ->map(function ($s) use ($tanggalHariIni) {
                // 1. Cek izin resmi siswa hari ini dari Orang Tua (IzinSiswa)
                $izin = IzinSiswa::where('nis', $s->nis)
                    ->where('status', 'Disetujui')
                    ->whereDate('tanggal_mulai', '<=', $tanggalHariIni)
                    ->whereDate('tanggal_selesai', '>=', $tanggalHariIni)
                    ->latest()
                    ->first();

                // Fallback jika ada record di model lama PengajuanIzinSiswa
                if (!$izin) {
                    $izinOld = PengajuanIzinSiswa::where('nis', $s->nis)
                        ->where('tanggal', $tanggalHariIni)
                        ->where('status', 'Disetujui')
                        ->first();
                    if ($izinOld) {
                        $izin = (object) [
                            'id'       => $izinOld->id,
                            'kategori' => $izinOld->jenis_izin,
                            'alasan'   => $izinOld->keterangan,
                        ];
                    }
                }

                // 2. Cek dispensasi aktif siswa hari ini (Dispensasi murni yang bukan catatan izin/sakit, mencakup multi-hari)
                $dispen = DispenSiswa::where('nis', $s->nis)
                    ->whereDate('tanggal', '<=', $tanggalHariIni)
                    ->where(function ($q) use ($tanggalHariIni) {
                        $q->whereNull('tanggal_selesai')
                          ->whereDate('tanggal', $tanggalHariIni)
                          ->orWhereDate('tanggal_selesai', '>=', $tanggalHariIni);
                    })
                    ->whereIn('status', ['Disetujui', 'Sedang di Luar'])
                    ->where(function ($q) {
                        $q->where('keperluan', 'NOT LIKE', 'Sakit%')
                          ->where('keperluan', 'NOT LIKE', 'Izin%');
                    })
                    ->latest('tanggal')
                    ->first();

                // Cek apakah siswa ini memiliki dispensasi hari ini yang sudah dikonfirmasi KEMBALI oleh Satpam
                $dispenSudahKembali = DispenSiswa::where('nis', $s->nis)
                    ->whereDate('tanggal', '<=', $tanggalHariIni)
                    ->where(function ($q) use ($tanggalHariIni) {
                        $q->whereNull('tanggal_selesai')
                          ->whereDate('tanggal', $tanggalHariIni)
                          ->orWhereDate('tanggal_selesai', '>=', $tanggalHariIni);
                    })
                    ->where('status', 'Sudah Kembali')
                    ->latest('updated_at')
                    ->first();

                // 3. Cek apakah ada status Sakit / Izin / Alpa / Terlambat dari jurnal jam sebelumnya hari ini (Berantai dari Guru Pertama)
                $presensiSebelumnya = JurnalDetailKetidakhadiran::where('id_siswa', $s->nis)
                    ->whereHas('jurnal', function ($q) use ($tanggalHariIni) {
                        $q->whereDate('tanggal', $tanggalHariIni);
                    })
                    ->whereIn('keterangan', ['Sakit', 'Izin', 'Alpa', 'Terlambat'])
                    ->latest('id_detail')
                    ->first();

                // 4. Cek apakah ada catatan siswa datang terlambat dari Guru Piket hari ini
                $siswaTelatHariIni = SiswaTelat::where('nis', $s->nis)
                    ->whereDate('tanggal', $tanggalHariIni)
                    ->latest()
                    ->first();

                $autoStatus = 'Hadir';
                $infoStatus = null;

                if ($izin) {
                    $autoStatus = ($izin->kategori === 'Sakit') ? 'Sakit' : 'Izin';
                    $alasanTeks = $izin->alasan ? ": {$izin->alasan}" : '';
                    $infoStatus = "Izin Resmi: {$izin->kategori}{$alasanTeks}";
                } elseif ($dispen) {
                    $autoStatus = 'Dispen';
                    $rentangTeks = ($dispen->tanggal_selesai && $dispen->tanggal_selesai !== $dispen->tanggal)
                        ? ' (s/d ' . \Carbon\Carbon::parse($dispen->tanggal_selesai)->translatedFormat('d M Y') . ')'
                        : '';

                    // Rangkum info jam pelajaran dan estimasi waktu keluar/kembali
                    $waktuRincian = [];
                    if (!empty($dispen->jam_ke)) {
                        $waktuRincian[] = $dispen->jam_ke;
                    }
                    if (!empty($dispen->jam_keluar_rencana)) {
                        $jamKeluar = substr($dispen->jam_keluar_rencana, 0, 5);
                        $jamKembali = !empty($dispen->jam_kembali_rencana) ? substr($dispen->jam_kembali_rencana, 0, 5) : 'Selesai';
                        $waktuRincian[] = "pk. {$jamKeluar}-{$jamKembali}";
                    }
                    $waktuTeks = !empty($waktuRincian) ? ' [' . implode(' | ', $waktuRincian) . ']' : '';

                    $infoStatus = "Dispensasi: {$dispen->keperluan}{$waktuTeks}{$rentangTeks}";
                } elseif ($dispenSudahKembali) {
                    // Siswa telah dikonfirmasi kembali ke sekolah oleh Satpam -> Hadir kembali di kelas
                    $autoStatus = 'Hadir';
                    $jamKembaliStr = $dispenSudahKembali->jam_kembali_aktual ? substr($dispenSudahKembali->jam_kembali_aktual, 0, 5) : null;
                    $infoStatus = $jamKembaliStr
                        ? "Sudah kembali ke sekolah (pk. {$jamKembaliStr} WIB)"
                        : "Sudah dikonfirmasi kembali ke sekolah oleh Satpam";
                } elseif ($presensiSebelumnya) {
                    $autoStatus = $presensiSebelumnya->keterangan;
                    $infoStatus = "Otomatis: Tercatat {$presensiSebelumnya->keterangan} pada sesi guru sebelumnya";
                } elseif ($siswaTelatHariIni) {
                    $autoStatus = 'Terlambat';
                    $jamTelat = substr($siswaTelatHariIni->jam_terlambat, 0, 5);
                    $infoStatus = "Terlambat Piket (pk. {$jamTelat})";
                }

                $s->auto_status = $autoStatus;
                $s->info_status = $infoStatus;
                $s->izin_hari_ini = $izin;
                return $s;
            });

        return view('guru.jurnal.create', compact('jadwal', 'siswaDiKelas', 'tanggalHariIni'));
    }

    /**
     * Simpan jurnal + data ketidakhadiran siswa.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $guru = $user->guru;

        if (!$guru) {
            abort(403, 'Data guru tidak ditemukan.');
        }

        $request->validate([
            'id_jadwal'            => 'required|exists:jadwal,id_jadwal',
            'tanggal'              => 'required|date',
            'materi'               => 'required|string|max:500',
            'status_kehadiran_guru'=> 'required|in:Hadir,Izin,Sakit,Tanpa Keterangan',
            'catatan'              => 'nullable|string|max:500',
            'ketidakhadiran'       => 'nullable|array',
            'foto_base64'          => 'required|string',
        ], [
            'foto_base64.required' => 'Bukti foto wajib diambil secara langsung dari kamera!',
        ]);

        $jadwal = Jadwal::where('id_jadwal', $request->id_jadwal)
            ->where('id_guru', $guru->id_guru)
            ->firstOrFail();

        $statusWaktu = $jadwal->statusWaktuMengajar();
        if ($statusWaktu === 'telat') {
            return back()->withErrors(['error' => 'Waktu pengisian jurnal telah habis! Sesi mengajar ini sudah tercatat Terlambat (Alpa) dan tidak dapat disimpan.']);
        }

        $existingRecord = JurnalMengajar::withTrashed()
            ->where('id_jadwal', $request->id_jadwal)
            ->whereDate('tanggal', $request->tanggal)
            ->first();

        if ($existingRecord) {
            if ($existingRecord->trashed()) {
                // Bersihkan record trashed lama beserta detailnya agar unique key bebas
                JurnalDetailKetidakhadiran::where('id_jurnal', $existingRecord->id_jurnal)->delete();
                FotoMengajar::where('id_jurnal', $existingRecord->id_jurnal)->delete();
                $existingRecord->forceDelete();
            } else {
                return back()->withErrors(['error' => 'Jurnal untuk sesi ini sudah pernah diisi.']);
            }
        }

        // 1. Simpan jurnal mengajar
        $jurnal = JurnalMengajar::create([
            'id_jadwal'             => $request->id_jadwal,
            'tanggal'               => $request->tanggal,
            'materi'                => $request->materi,
            'status_kehadiran_guru' => $request->status_kehadiran_guru,
            'catatan'               => $request->catatan,
            'dicatat_pada'          => now(),
        ]);

        // 2. Simpan Foto (Ubah teks Base64 menjadi file gambar fisik)
        if ($request->filled('foto_base64')) {
            $image_parts = explode(";base64,", $request->foto_base64);
            
            if (count($image_parts) == 2) {
                $image_base64 = base64_decode($image_parts[1]);
                $fileName = 'jurnal_fotos/' . uniqid() . '.png';
                
                \Illuminate\Support\Facades\Storage::disk('public')->put($fileName, $image_base64);
                
                FotoMengajar::create([
                    'id_jurnal'    => $jurnal->id_jurnal,
                    'foto_path'    => $fileName,
                    'diambil_pada' => now(),
                ]);
            }
        }

        // 3. Simpan ketidakhadiran siswa (Sakit, Izin, Alpa, Dispen, Terlambat)
        $ketidakhadiranInput = $request->input('ketidakhadiran') ?? $request->input('ketidakhadiran_mob') ?? [];
        $ketidakhadiranMap = [];

        if (!empty($ketidakhadiranInput) && is_array($ketidakhadiranInput)) {
            foreach ($ketidakhadiranInput as $nis => $keterangan) {
                if (in_array($keterangan, ['Sakit', 'Izin', 'Alpa', 'Dispen', 'Terlambat'])) {
                    $refIzin = PengajuanIzinSiswa::where('nis', $nis)
                        ->where('tanggal', $request->tanggal)
                        ->where('status', 'Disetujui')
                        ->first();

                    JurnalDetailKetidakhadiran::create([
                        'id_jurnal'    => $jurnal->id_jurnal,
                        'id_siswa'     => $nis,
                        'keterangan'   => ($keterangan === 'Dispen') ? 'Izin' : $keterangan,
                        'ref_izin_id'  => $refIzin ? $refIzin->id : null,
                        'dicatat_oleh' => $user->id,
                    ]);

                    $ketidakhadiranMap[$nis] = $keterangan;
                }
            }

            // Kirim notifikasi WA langsung (instan) ke nomor Orang Tua & Wali Kelas saat jurnal disimpan
            // (Meliputi status: Sakit, Izin, Dispen, dan Alpa agar orang tua mengetahui kondisi real kehadiran anaknya)
            if (!empty($ketidakhadiranMap)) {
                WhatsAppService::sendKetidakhadiranSiswaNotification($ketidakhadiranMap, $jurnal);
            }
        }

        return redirect()->route('guru.dashboard')->with('success', 'Jurnal mengajar dan presensi berhasil disimpan!');
    }

    /**
     * Tampilkan Detail Jurnal Mengajar
     */
    public function show($id_jurnal)
    {
        $user = Auth::user();
        $guru = $user->guru;

        $jurnal = JurnalMengajar::with([
            'foto',
            'jadwal.guru',
            'jadwal.kelas',
            'jadwal.mapel',
            'jadwal.ruangan',
            'detailKetidakhadiran.siswa'
        ])
        ->where('id_jurnal', $id_jurnal)
        ->firstOrFail();

        return view('guru.jurnal.show', compact('jurnal'));
    }

    /**
     * Rekap riwayat jurnal guru yang sedang login
     */
    public function rekap(Request $request)
    {
        $user = Auth::user();
        $guru = $user->guru;

        if (!$guru) {
            abort(403, 'Data guru tidak ditemukan.');
        }

        $query = JurnalMengajar::with(['jadwal.kelas', 'jadwal.mapel', 'jadwal.ruangan', 'foto'])
            ->whereHas('jadwal', function ($q) use ($guru) {
                $q->where('id_guru', $guru->id_guru);
            });

        if ($request->filled('dari')) {
            $query->whereDate('tanggal', '>=', $request->dari);
        }

        if ($request->filled('sampai')) {
            $query->whereDate('tanggal', '<=', $request->sampai);
        }

        if ($request->filled('bulan')) {
            $query->whereMonth('tanggal', $request->bulan);
        }

        $rekapList = $query->orderBy('tanggal', 'desc')->paginate(15);
        $riwayatJurnal = $rekapList;

        return view('guru.jurnal.rekap', compact('rekapList', 'riwayatJurnal', 'guru'));
    }

    /**
     * Rincian Sesi Mengajar Tertunggak (Poin 8 & 10)
     */
    public function tertunggak()
    {
        $user = Auth::user();
        $guru = $user->guru;

        if (!$guru) {
            abort(403, 'Data guru tidak ditemukan.');
        }

        $startOfWeek = Carbon::now()->startOfWeek(Carbon::MONDAY);
        $today = Carbon::today();
        $hariList = ['Senin','Selasa','Rabu','Kamis','Jumat'];

        $jadwalGuru = Jadwal::with(['kelas', 'mapel', 'ruangan'])
            ->where('id_guru', $guru->id_guru)
            ->get();

        $daftarTertunggak = [];

        foreach ($jadwalGuru as $j) {
            $dayIndex = array_search($j->hari, $hariList);
            if ($dayIndex === false) continue;
            
            $tanggalJadwal = $startOfWeek->copy()->addDays($dayIndex)->toDateString();
            
            if ($tanggalJadwal <= $today->toDateString()) {
                $jurnal = JurnalMengajar::where('id_jadwal', $j->id_jadwal)
                    ->whereDate('tanggal', $tanggalJadwal)
                    ->first();

                if (!$jurnal) {
                    $izin = \App\Models\IzinGuru::where('id_guru', $guru->id_guru)
                        ->where('status_akhir', 'Disetujui')
                        ->whereDate('tanggal_mulai', '<=', $tanggalJadwal)
                        ->whereDate('tanggal_selesai', '>=', $tanggalJadwal)
                        ->first();

                    // Cek event sekolah / pulang pagi
                    $times   = $j->getWaktuMulaiSelesai();
                    $exempt  = EventSekolah::cekExempt($tanggalJadwal, $times['waktu_mulai'] ?? null);

                    $isToday     = ($tanggalJadwal === $today->toDateString());
                    $statusWaktu = $isToday ? $j->statusWaktuMengajar() : 'telat';

                    if ($exempt['exempt']) {
                        $keterangan = $exempt['alasan'];
                    } elseif ($izin) {
                        $keterangan = "Izin Sah ({$izin->alasan})";
                    } else {
                        $keterangan = 'Alpa (Belum Diisi)';
                    }

                    $daftarTertunggak[] = [
                        'id_jadwal'      => $j->id_jadwal,
                        'tanggal'        => $tanggalJadwal,
                        'hari'           => $j->hari,
                        'jam_ke'         => "Jam Ke-{$j->jam_mulai} s/d {$j->jam_selesai}",
                        'kelas'          => $j->kelas ? $j->kelas->nama_kelas : '-',
                        'mapel'          => $j->mapel ? $j->mapel->nama_mapel : '-',
                        'ruangan'        => $j->ruangan ? $j->ruangan->nama_ruangan : '-',
                        'keterangan'     => $keterangan,
                        'is_exempt'      => $exempt['exempt'],
                        'is_today'       => $isToday,
                        'status_waktu'   => $statusWaktu,
                    ];
                }
            }
        }

        return view('guru.jurnal.tertunggak', compact('daftarTertunggak'));
    }
}
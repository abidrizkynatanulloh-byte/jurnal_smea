<?php

namespace App\Http\Controllers\Guru;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Jadwal;
use App\Models\JurnalMengajar;
use App\Models\JurnalDetailKetidakhadiran;
use App\Models\Siswa;
use App\Models\PengajuanIzinSiswa;
use App\Models\DispenSiswa;
use App\Models\FotoMengajar;
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
        } elseif ($statusWaktu === 'telat') {
            return redirect()->route('guru.dashboard')
                ->withErrors(['error' => 'Batas waktu pengisian jurnal (termasuk toleransi 10 menit) sudah habis. Kamu tercatat tidak hadir (Alpa) pada sesi ini.']);
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
                // 1. Cek izin resmi siswa hari ini
                $izin = PengajuanIzinSiswa::where('nis', $s->nis)
                    ->where('tanggal', $tanggalHariIni)
                    ->where('status', 'Disetujui')
                    ->first();

                // 2. Cek dispensasi aktif siswa hari ini
                $dispen = DispenSiswa::where('nis', $s->nis)
                    ->where('tanggal', $tanggalHariIni)
                    ->whereIn('status', ['Disetujui', 'Sedang di Luar'])
                    ->first();

                // 3. Cek apakah ada status Sakit / Izin dari jurnal jam sebelumnya hari ini
                $presensiSebelumnya = JurnalDetailKetidakhadiran::where('id_siswa', $s->nis)
                    ->whereHas('jurnal', function ($q) use ($tanggalHariIni) {
                        $q->whereDate('tanggal', $tanggalHariIni);
                    })
                    ->whereIn('keterangan', ['Sakit', 'Izin'])
                    ->latest()
                    ->first();

                $autoStatus = 'Hadir';
                $infoStatus = null;

                if ($izin) {
                    $autoStatus = ($izin->jenis_izin === 'Sakit') ? 'Sakit' : 'Izin';
                    $infoStatus = "Izin Resmi: {$izin->jenis_izin}";
                } elseif ($dispen) {
                    $autoStatus = 'Dispen';
                    $infoStatus = "Dispensasi: {$dispen->keperluan}";
                } elseif ($presensiSebelumnya) {
                    $autoStatus = $presensiSebelumnya->keterangan;
                    $infoStatus = "Otomatis: Tercatat {$presensiSebelumnya->keterangan} pada sesi sebelumnya";
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

        $sudahAda = JurnalMengajar::where('id_jadwal', $request->id_jadwal)
            ->whereDate('tanggal', $request->tanggal)
            ->exists();

        if ($sudahAda) {
            return back()->withErrors(['error' => 'Jurnal untuk sesi ini sudah pernah diisi.']);
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

        // 3. Simpan ketidakhadiran siswa (Sakit, Izin, Alpa, Dispen)
        if ($request->filled('ketidakhadiran')) {
            foreach ($request->ketidakhadiran as $nis => $keterangan) {
                if (in_array($keterangan, ['Sakit', 'Izin', 'Alpa', 'Dispen'])) {
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
                }
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

        $query = JurnalMengajar::with(['jadwal.kelas', 'jadwal.mapel', 'jadwal.ruangan', 'foto'])
            ->whereHas('jadwal', function ($q) use ($guru) {
                $q->where('id_guru', $guru->id_guru);
            });

        if ($request->filled('bulan')) {
            $query->whereMonth('tanggal', $request->bulan);
        }

        $riwayatJurnal = $query->orderBy('tanggal', 'desc')->paginate(15);

        return view('guru.jurnal.rekap', compact('riwayatJurnal'));
    }

    /**
     * Rincian Sesi Mengajar Tertunggak (Poin 8 & 10)
     */
    public function tertunggak()
    {
        $user = Auth::user();
        $guru = $user->guru;

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

                    $daftarTertunggak[] = [
                        'id_jadwal'      => $j->id_jadwal,
                        'tanggal'        => $tanggalJadwal,
                        'hari'           => $j->hari,
                        'jam_ke'         => "Jam Ke-{$j->jam_mulai} s/d {$j->jam_selesai}",
                        'kelas'          => $j->kelas ? $j->kelas->nama_kelas : '-',
                        'mapel'          => $j->mapel ? $j->mapel->nama_mapel : '-',
                        'ruangan'        => $j->ruangan ? $j->ruangan->nama_ruangan : '-',
                        'keterangan'     => $izin ? "Izin Sah ({$izin->alasan})" : 'Alpa (Belum Diisi)',
                        'is_today'       => ($tanggalJadwal === $today->toDateString()),
                    ];
                }
            }
        }

        return view('guru.jurnal.tertunggak', compact('daftarTertunggak'));
    }
}
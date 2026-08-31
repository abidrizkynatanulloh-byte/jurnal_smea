<?php

namespace App\Http\Controllers\Guru;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Jadwal;
use App\Models\JurnalMengajar;
use App\Models\JurnalDetailKetidakhadiran;
use App\Models\Siswa;
use App\Models\PengajuanIzinSiswa;
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

        $tanggalHariIni = Carbon::today()->toDateString();

        // Cek apakah jurnal hari ini sudah ada
        $jurnalAda = JurnalMengajar::where('id_jadwal', $id_jadwal)
            ->whereDate('tanggal', $tanggalHariIni)
            ->first();

        if ($jurnalAda) {
            return redirect()->route('guru.jurnal.show', $jurnalAda->id_jurnal)
                ->with('info', 'Jurnal untuk sesi ini sudah pernah diisi.');
        }

        // Ambil daftar siswa di kelas ini + cek apakah ada izin yang disetujui hari ini
        $siswaDiKelas = Siswa::where('id_kelas', $jadwal->id_kelas)
            ->orderBy('nama_siswa')
            ->get()
            ->map(function ($s) use ($tanggalHariIni) {
                // Cek izin yang disetujui hari ini
                $izin = PengajuanIzinSiswa::where('nis', $s->nis)
                    ->where('tanggal', $tanggalHariIni)
                    ->where('status', 'Disetujui')
                    ->first();
                $s->izin_hari_ini = $izin; // null jika tidak ada
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
            'ketidakhadiran'       => 'nullable|array', // ['nis' => 'Sakit'/'Izin'/'Alpa']
        ]);

        // Validasi: jadwal harus milik guru yang login
        $jadwal = Jadwal::where('id_jadwal', $request->id_jadwal)
            ->where('id_guru', $guru->id_guru)
            ->firstOrFail();

        // Cek duplikat
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

        // 2. Simpan ketidakhadiran siswa (yang tidak hadir saja)
        if ($request->filled('ketidakhadiran')) {
            foreach ($request->ketidakhadiran as $nis => $keterangan) {
                if (in_array($keterangan, ['Sakit', 'Izin', 'Alpa'])) {
                    // Cek ref izin jika ada
                    $refIzin = PengajuanIzinSiswa::where('nis', $nis)
                        ->where('tanggal', $request->tanggal)
                        ->where('status', 'Disetujui')
                        ->first();

                    JurnalDetailKetidakhadiran::create([
                        'id_jurnal'    => $jurnal->id_jurnal,
                        'id_siswa'     => $nis,
                        'keterangan'   => $keterangan,
                        'ref_izin_id'  => $refIzin ? $refIzin->id : null,
                        'dicatat_oleh' => $user->id,
                    ]);
                }
            }
        }

        return redirect()->route('guru.dashboard')
            ->with('success', 'Jurnal berhasil disimpan! Terima kasih.');
    }

    /**
     * Detail jurnal yang sudah diisi.
     */
    public function show($id_jurnal)
    {
        $user   = Auth::user();
        $guru   = $user->guru;

        $jurnal = JurnalMengajar::with([
            'jadwal.kelas',
            'jadwal.mapel',
            'jadwal.ruangan',
        ])->findOrFail($id_jurnal);

        // Pastikan jurnal ini milik guru yang login
        if ($jurnal->jadwal->id_guru !== $guru->id_guru) {
            abort(403);
        }

        // Daftar siswa yang tidak hadir pada jurnal ini
        $ketidakhadiran = JurnalDetailKetidakhadiran::with('siswa')
            ->where('id_jurnal', $id_jurnal)
            ->get();

        return view('guru.jurnal.show', compact('jurnal', 'ketidakhadiran'));
    }

    /**
     * Rekap jurnal guru (semua yang sudah pernah diisi).
     */
    public function rekap(Request $request)
    {
        $user = Auth::user();
        $guru = $user->guru;

        $query = JurnalMengajar::with(['jadwal.kelas', 'jadwal.mapel'])
            ->whereHas('jadwal', fn($q) => $q->where('id_guru', $guru->id_guru));

        // Filter tanggal
        if ($request->filled('dari')) {
            $query->whereDate('tanggal', '>=', $request->dari);
        }
        if ($request->filled('sampai')) {
            $query->whereDate('tanggal', '<=', $request->sampai);
        }

        $rekapList = $query->orderBy('tanggal', 'desc')->paginate(15)->withQueryString();

        return view('guru.jurnal.rekap', compact('rekapList', 'guru'));
    }
}
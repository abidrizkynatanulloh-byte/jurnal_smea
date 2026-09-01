<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Siswa;
use App\Models\DispenSiswa;
use App\Models\SiswaTelat;
use App\Models\Jadwal;
use App\Models\JurnalMengajar;
use App\Models\TugasKelasKosong;
use App\Models\IzinGuru;
use App\Models\AuditLog;
use App\Models\Notifikasi;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PiketController extends Controller
{
    /**
     * Menampilkan Dashboard Guru Piket (Form Input Dispen & Siswa Telat & Pantauan Hari Ini).
     */
    public function index()
    {
        $user = Auth::user();
        if ($user->role === 'guru' && $user->guru) {
            if (!$user->guru->isPiketHariIni()) {
                return redirect()->route('guru.dashboard')->with('error', 'Akses ditolak. Anda tidak terdaftar bertugas piket hari ini.');
            }
        }

        $daftarSiswa = Siswa::with('kelas')->orderBy('nama_siswa', 'asc')->get();

        $dispenHariIni = DispenSiswa::with(['siswa.kelas', 'disetujuiOleh'])
            ->whereDate('tanggal', date('Y-m-d'))
            ->orderBy('created_at', 'desc')
            ->get();

        $siswaTelatHariIni = SiswaTelat::with(['siswa.kelas', 'guruPiket'])
            ->whereDate('tanggal', date('Y-m-d'))
            ->orderBy('created_at', 'desc')
            ->get();

        $izinGuruPending = IzinGuru::with('guru')
            ->where('status_piket', 'Menunggu')
            ->where('status_akhir', '!=', 'Ditolak')
            ->orderBy('id', 'desc')
            ->get();

        return view('piket.dashboard', compact('daftarSiswa', 'dispenHariIni', 'siswaTelatHariIni', 'izinGuruPending'));
    }

    /**
     * Layar Monitoring Kondisi Seluruh Kelas Hari Ini Real-Time.
     */
    public function monitoringKelas()
    {
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

        // Ambil semua jadwal KBM hari ini
        $jadwalHariIni = Jadwal::with(['guru', 'kelas', 'mapel', 'ruangan'])
            ->where('hari', $namaHari)
            ->orderBy('jam_mulai')
            ->get();

        // Cek guru yang punya izin sah hari ini
        $guruIzinHariIni = IzinGuru::where('status_akhir', 'Disetujui')
            ->where('tanggal_mulai', '<=', $hariIni)
            ->where('tanggal_selesai', '>=', $hariIni)
            ->pluck('id_guru')
            ->toArray();

        // Ambil tugas kelas kosong yang telah tercatat
        $tugasList = TugasKelasKosong::with(['guru', 'kelas'])
            ->where('tanggal', $hariIni)
            ->get();

        return view('piket.monitoring-kelas', compact(
            'jadwalHariIni',
            'hariIni',
            'namaHari',
            'guruIzinHariIni',
            'tugasList'
        ));
    }

    /**
     * Guru piket / sekolah mencatat tugas untuk kelas yang gurunya tidak hadir / alpa.
     */
    public function storeTugasKelas(Request $request)
    {
        $request->validate([
            'id_guru'         => 'required',
            'id_kelas'        => 'required',
            'deskripsi_tugas' => 'required|string',
        ]);

        TugasKelasKosong::create([
            'id_jadwal'       => $request->id_jadwal,
            'tanggal'         => Carbon::today()->toDateString(),
            'id_guru'         => $request->id_guru,
            'id_kelas'        => $request->id_kelas,
            'deskripsi_tugas' => $request->deskripsi_tugas,
            'status'          => 'Diberikan',
        ]);

        AuditLog::log(
            'Pencatatan Tugas Kelas Kosong',
            "Guru Piket mencatat tugas untuk kelas ID: {$request->id_kelas}"
        );

        return back()->with('success', 'Tugas belajar untuk kelas tersebut berhasil dicatat dan dipublikasikan.');
    }

    /**
     * Guru Piket menyimpan pengajuan dispen siswa & mengirim notif ke Wakasis Siswa.
     */
    public function storeDispen(Request $request)
    {
        $request->validate([
            'nis'                 => 'required|exists:siswa,nis',
            'keperluan'           => 'required|string|max:255',
            'jam_ke'              => 'nullable|string|max:50',
            'jam_keluar_rencana'  => 'required',
            'jam_kembali_rencana' => 'nullable',
        ], [
            'nis.required'                => 'Silakan pilih siswa yang mengajukan dispen.',
            'keperluan.required'          => 'Alasan / keperluan dispen wajib diisi.',
            'jam_keluar_rencana.required' => 'Rencana jam keluar wajib diisi.',
        ]);

        DB::beginTransaction();

        try {
            $dispen = DispenSiswa::create([
                'nis'                 => $request->nis,
                'keperluan'           => $request->keperluan,
                'jam_ke'              => $request->jam_ke,
                'tanggal'             => date('Y-m-d'),
                'jam_keluar_rencana'  => $request->jam_keluar_rencana,
                'jam_kembali_rencana' => $request->jam_kembali_rencana,
                'status'              => 'Menunggu',
            ]);

            AuditLog::log(
                'Pengajuan Dispen Siswa',
                "Guru Piket mengajukan dispen untuk siswa NIS: {$request->nis} ({$request->keperluan})"
            );

            DB::commit();

            return redirect()->route('piket.dashboard')
                             ->with('success', 'Pengajuan dispen siswa berhasil dikirim ke Waka Kesiswaan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                             ->withInput()
                             ->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    /**
     * Guru Piket mencatat siswa yang datang terlambat (telat).
     */
    public function storeSiswaTelat(Request $request)
    {
        $request->validate([
            'nis'           => 'required|exists:siswa,nis',
            'jam_terlambat' => 'required',
            'alasan'        => 'nullable|string|max:255',
            'tindakan'      => 'nullable|string|max:255',
        ], [
            'nis.required'           => 'Silakan pilih siswa yang terlambat.',
            'jam_terlambat.required' => 'Jam kedatangan terlambat wajib diisi.',
        ]);

        try {
            $idGuruPiket = Auth::user()->id_guru ?? null;

            SiswaTelat::create([
                'nis'           => $request->nis,
                'tanggal'       => date('Y-m-d'),
                'jam_terlambat' => $request->jam_terlambat,
                'alasan'        => $request->alasan,
                'tindakan'      => $request->tindakan,
                'id_guru_piket' => $idGuruPiket,
            ]);

            AuditLog::log(
                'Pencatatan Siswa Terlambat',
                "Guru Piket mencatat keterlambatan siswa NIS: {$request->nis} pada jam {$request->jam_terlambat}"
            );

            return redirect()->route('piket.dashboard')
                             ->with('success', 'Data siswa terlambat berhasil dicatat.');

        } catch (\Exception $e) {
            return redirect()->back()
                             ->withInput()
                             ->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    /**
     * Guru Piket menyetujui izin guru.
     */
    public function approveIzinGuru($id)
    {
        $izin = \App\Models\IzinGuru::findOrFail($id);
        $izin->status_piket = 'Disetujui';
        $izin->cekDanUpdateStatusAkhir();

        AuditLog::log(
            'Persetujuan Izin Guru oleh Guru Piket',
            "Guru Piket menyetujui izin Guru: {$izin->guru->nama_guru} ({$izin->alasan})"
        );

        return back()->with('success', "Izin Guru {$izin->guru->nama_guru} telah disetujui oleh Guru Piket.");
    }

    /**
     * Guru Piket menolak izin guru.
     */
    public function rejectIzinGuru(Request $request, $id)
    {
        $izin = \App\Models\IzinGuru::findOrFail($id);
        $izin->status_piket = 'Ditolak';
        $izin->catatan_penolakan = $request->input('catatan', 'Ditolak oleh Guru Piket');
        $izin->cekDanUpdateStatusAkhir();

        AuditLog::log(
            'Penolakan Izin Guru oleh Guru Piket',
            "Guru Piket menolak izin Guru: {$izin->guru->nama_guru}."
        );

        return back()->with('info', "Izin Guru {$izin->guru->nama_guru} telah ditolak oleh Guru Piket.");
    }
}
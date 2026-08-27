<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\DispenSiswa;
use App\Models\Notifikasi;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class PiketController
{
    /**
     * Menampilkan Dashboard Guru Piket (Form Input Dispen & Pantauan Hari Ini).
     */
    public function index()
    {
        // 1. Ambil daftar semua siswa untuk pilihan dropdown
        $daftarSiswa = Siswa::orderBy('nama_siswa', 'asc')->get();

        // 2. Ambil daftar dispen yang diajukan hari ini
        $dispenHariIni = DispenSiswa::with('siswa')
                            ->whereDate('tanggal', date('Y-m-d'))
                            ->orderBy('created_at', 'desc')
                            ->get();

        return view('piket.dashboard', compact('daftarSiswa', 'dispenHariIni'));
    }

    /**
     * Guru Piket menyimpan pengajuan dispen siswa & mengirim notif ke Wakasis Siswa.
     */
    public function storeDispen(Request $request)
    {
        // 1. Validasi input
        $request->validate([
            'nis'                 => 'required|exists:siswa,nis',
            'keperluan'           => 'required|string|max:255',
            'jam_keluar_rencana'  => 'required',
            'jam_kembali_rencana' => 'nullable',
        ], [
            'nis.required'                => 'Silakan pilih siswa yang mengajukan dispen.',
            'keperluan.required'          => 'Alasan / keperluan dispen wajib diisi.',
            'jam_keluar_rencana.required' => 'Rencana jam keluar wajib diisi.',
        ]);

        DB::beginTransaction();

        try {
            // 2. Simpan pengajuan ke tabel dispen_siswa
            $dispen = DispenSiswa::create([
                'nis'                 => $request->nis,
                'keperluan'           => $request->keperluan,
                'tanggal'             => date('Y-m-d'),
                'jam_keluar_rencana'  => $request->jam_keluar_rencana,
                'jam_kembali_rencana' => $request->jam_kembali_rencana,
                'status'              => 'Menunggu',
            ]);

            // 3. Ambil data siswa untuk teks notifikasi
            $siswa = Siswa::where('nis', $request->nis)->first();

            // 4. Cari user Wakasis Siswa untuk dikirimi notifikasi
            $wakasisList = User::where('role', 'wakasis_siswa')->get();

            foreach ($wakasisList as $wakasis) {
                Notifikasi::create([
                    'untuk_user_id' => $wakasis->id,
                    'judul'         => 'Pengajuan Dispen Siswa Baru',
                    'pesan'         => 'Siswa ' . $siswa->nama_siswa . ' (NIS: ' . $siswa->nis . ') mengajukan dispen: "' . $request->keperluan . '" jam ' . $request->jam_keluar_rencana,
                    'jenis'         => 'dispen_siswa',
                    'ref_id'        => $dispen->id,
                    'sudah_dibaca'  => 0,
                    'created_at'    => now(),
                ]);
            }

            DB::commit();

            return redirect()->route('piket.dashboard')->with('success', 'Pengajuan dispen untuk siswa ' . $siswa->nama_siswa . ' berhasil dikirim ke Kesiswaan!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Gagal mengajukan dispen: ' . $e->getMessage()]);
        }
    }
}
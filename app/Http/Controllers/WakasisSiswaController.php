<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DispenSiswa;
use App\Models\Notifikasi;
use Illuminate\Support\Facades\Auth;

class WakasisSiswaController
{
    /**
     * Menampilkan Dashboard Wakil Kesiswaan (Siswa) - Daftar Pengajuan Dispen.
     */
    public function index()
    {
        // Ambil semua pengajuan dispen yang pending (Menunggu)
        $pendingDispen = DispenSiswa::with('siswa')
            ->where('status', 'Menunggu')
            ->orderBy('created_at', 'desc')
            ->get();

        // Ambil riwayat pengajuan dispen hari ini / kemarin yang sudah diproses
        $historyDispen = DispenSiswa::with(['siswa', 'disetujuiOleh'])
            ->whereIn('status', ['Disetujui', 'Ditolak'])
            ->orderBy('updated_at', 'desc')
            ->paginate(15);

        return view('wakasis.siswa.dashboard', compact('pendingDispen', 'historyDispen'));
    }

    /**
     * Menyetujui pengajuan dispen siswa.
     */
    public function approve($id)
    {
        $dispen = DispenSiswa::findOrFail($id);
        $dispen->update([
            'status' => 'Disetujui',
            'disetujui_oleh' => Auth::id(),
        ]);

        // Tandai notifikasi terkait sebagai sudah dibaca
        Notifikasi::where('ref_id', $id)
            ->where('untuk_user_id', Auth::id())
            ->update(['sudah_dibaca' => 1]);

        return redirect()->route('wakasis.siswa.dashboard')
            ->with('success', 'Pengajuan dispensasi siswa berhasil disetujui!');
    }

    /**
     * Menolak pengajuan dispen siswa.
     */
    public function reject(Request $request, $id)
    {
        $dispen = DispenSiswa::findOrFail($id);
        
        $request->validate([
            'catatan_wakasis' => 'nullable|string|max:255',
        ]);

        $dispen->update([
            'status' => 'Ditolak',
            'disetujui_oleh' => Auth::id(),
            'catatan_wakasis' => $request->catatan_wakasis,
        ]);

        // Tandai notifikasi terkait sebagai sudah dibaca
        Notifikasi::where('ref_id', $id)
            ->where('untuk_user_id', Auth::id())
            ->update(['sudah_dibaca' => 1]);

        return redirect()->route('wakasis.siswa.dashboard')
            ->with('success', 'Pengajuan dispensasi siswa telah ditolak.');
    }
}

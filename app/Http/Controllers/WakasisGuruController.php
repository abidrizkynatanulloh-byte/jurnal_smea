<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\IzinGuru;
use App\Models\AuditLog;

class WakasisGuruController extends Controller
{
    /**
     * Dashboard Waka Kurikulum & SDM Kepegawaian untuk verifikasi izin guru.
     */
    public function index()
    {
        // 1. Menunggu Waka Kurikulum (Tahap 1)
        $menungguWaka = IzinGuru::with('guru')
            ->where('status_waka', 'Menunggu')
            ->orderBy('id', 'desc')
            ->get();

        // 2. Menunggu SDM Kepegawaian (Tahap 2 - setelah disetujui Waka)
        $menungguSdm = IzinGuru::with('guru')
            ->where('status_waka', 'Disetujui')
            ->where('status_sdm', 'Menunggu')
            ->orderBy('id', 'desc')
            ->get();

        // 3. Riwayat Izin Keseluruhan
        $riwayatIzin = IzinGuru::with('guru')
            ->orderBy('id', 'desc')
            ->take(15)
            ->get();

        return view('wakasis.guru.dashboard', compact(
            'menungguWaka',
            'menungguSdm',
            'riwayatIzin'
        ));
    }

    /**
     * Waka Kurikulum menyetujui tahap 1.
     */
    public function approveWaka($id)
    {
        $izin = IzinGuru::findOrFail($id);
        $izin->status_waka = 'Disetujui';
        $izin->cekDanUpdateStatusAkhir();

        AuditLog::log('Persetujuan Izin Guru - Waka Kurikulum', "Waka Kurikulum menyetujui izin {$izin->guru->nama_guru}");

        return back()->with('success', "Izin Guru {$izin->guru->nama_guru} disetujui oleh Waka Kurikulum.");
    }

    /**
     * Waka Kurikulum menolak.
     */
    public function rejectWaka(Request $request, $id)
    {
        $izin = IzinGuru::findOrFail($id);
        $izin->status_waka = 'Ditolak';
        $izin->catatan_penolakan = $request->input('catatan', 'Ditolak oleh Waka Kurikulum');
        $izin->cekDanUpdateStatusAkhir();

        AuditLog::log('Penolakan Izin Guru - Waka Kurikulum', "Waka Kurikulum menolak izin {$izin->guru->nama_guru}");

        return back()->with('info', "Izin Guru {$izin->guru->nama_guru} telah ditolak.");
    }

    /**
     * SDM menyetujui tahap 2 (diteruskan ke Kepala Sekolah).
     */
    public function approveSdm($id)
    {
        $izin = IzinGuru::findOrFail($id);
        $izin->update(['status_sdm' => 'Disetujui']);

        AuditLog::log('Persetujuan Izin Guru - Bagian SDM', "SDM menyetujui izin {$izin->guru->nama_guru}");

        return back()->with('success', "Izin Guru {$izin->guru->nama_guru} disetujui oleh SDM dan diteruskan ke Kepala Sekolah untuk pengesahan akhir.");
    }

    /**
     * SDM menolak tahap 2.
     */
    public function rejectSdm(Request $request, $id)
    {
        $izin = IzinGuru::findOrFail($id);
        $izin->update([
            'status_sdm'        => 'Ditolak',
            'status_akhir'      => 'Ditolak',
            'catatan_penolakan' => $request->input('catatan', 'Ditolak oleh Bagian SDM'),
        ]);

        AuditLog::log('Penolakan Izin Guru - Bagian SDM', "SDM menolak izin {$izin->guru->nama_guru}");

        return back()->with('info', "Izin Guru {$izin->guru->nama_guru} telah ditolak oleh SDM.");
    }
}

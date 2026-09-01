<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\DispenSiswa;
use App\Models\AuditLog;
use Carbon\Carbon;

class SatpamController extends Controller
{
    public function index(Request $request)
    {
        $hariIni = Carbon::today()->toDateString();
        $jamSekarang = Carbon::now()->format('H:i:s');
        $search = $request->query('search');

        // Query dasar dispensasi hari ini yang sudah disetujui Waka
        $query = DispenSiswa::with(['siswa.kelas', 'disetujuiOleh'])
            ->where('tanggal', $hariIni);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nis', 'like', "%{$search}%")
                  ->orWhereHas('siswa', function ($sub) use ($search) {
                      $sub->where('nama_siswa', 'like', "%{$search}%");
                  });
            });
        }

        $allDispen = $query->get();

        // 1. Siswa dengan izin valid siap keluar gerbang
        $siapKeluar = $allDispen->where('status', 'Disetujui');

        // 2. Siswa yang saat ini sedang berada di luar lingkungan sekolah
        $sedangDiLuar = $allDispen->where('status', 'Sedang di Luar');

        // 3. Siswa yang terlambat kembali (jam_kembali_rencana < jam sekarang)
        $terlambatKembali = $sedangDiLuar->filter(function ($item) use ($jamSekarang) {
            return $item->jam_kembali_rencana && $item->jam_kembali_rencana < $jamSekarang;
        });

        // 4. Riwayat siswa yang sudah kembali ke sekolah hari ini
        $sudahKembali = $allDispen->where('status', 'Sudah Kembali');

        return view('satpam.dashboard', compact(
            'siapKeluar',
            'sedangDiLuar',
            'terlambatKembali',
            'sudahKembali',
            'search',
            'hariIni',
            'jamSekarang'
        ));
    }

    /**
     * Satpam mengonfirmasi siswa meninggalkan lingkungan sekolah (melewati gerbang).
     */
    public function konfirmasiKeluar($id)
    {
        $dispen = DispenSiswa::findOrFail($id);
        
        $jamKeluar = Carbon::now()->format('H:i:s');
        $dispen->update([
            'status'             => 'Sedang di Luar',
            'jam_keluar_aktual'  => $jamKeluar,
            'dicatat_satpam'     => Auth::id(),
        ]);

        AuditLog::log(
            'Konfirmasi Siswa Keluar',
            "Siswa {$dispen->nis} ({$dispen->siswa->nama_siswa}) keluar gerbang pukul {$jamKeluar}. Keperluan: {$dispen->keperluan}"
        );

        return back()->with('success', "Konfirmasi Keluar Berhasil! Siswa {$dispen->siswa->nama_siswa} dicatat keluar gerbang pukul {$jamKeluar}.");
    }

    /**
     * Satpam mengonfirmasi siswa telah kembali ke lingkungan sekolah.
     */
    public function konfirmasiKembali($id)
    {
        $dispen = DispenSiswa::findOrFail($id);
        
        $jamKembali = Carbon::now()->format('H:i:s');
        $dispen->update([
            'status'             => 'Sudah Kembali',
            'jam_kembali_aktual' => $jamKembali,
        ]);

        AuditLog::log(
            'Konfirmasi Siswa Kembali',
            "Siswa {$dispen->nis} ({$dispen->siswa->nama_siswa}) kembali ke sekolah pukul {$jamKembali}."
        );

        return back()->with('success', "Konfirmasi Kembali Berhasil! Siswa {$dispen->siswa->nama_siswa} dicatat kembali ke sekolah.");
    }
}

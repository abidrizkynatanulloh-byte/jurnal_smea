<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Guru;
use App\Models\Siswa;
use App\Models\Jadwal;
use App\Models\JurnalMengajar;
use App\Models\JurnalDetailKetidakhadiran;
use App\Models\DispenSiswa;
use App\Models\IzinGuru;
use App\Models\AuditLog;
use Carbon\Carbon;

class KepsekController extends Controller
{
    public function index()
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

        // 1. Total Entitas Utama
        $totalGuru  = Guru::count();
        $totalSiswa = Siswa::count();

        // 2. Jadwal & Jurnal Hari Ini
        $jadwalHariIni = Jadwal::with(['guru', 'kelas', 'mapel', 'ruangan'])
            ->where('hari', $namaHari)
            ->get();
        $totalSesiHariIni = $jadwalHariIni->count();

        $jurnalHariIni = JurnalMengajar::with('jadwal')
            ->where('tanggal', $hariIni)
            ->get();
        $totalJurnalTerisi = $jurnalHariIni->count();
        
        // Persentase Kehadiran Guru Hari Ini
        $persenGuruHadir = $totalSesiHariIni > 0 
            ? round(($totalJurnalTerisi / $totalSesiHariIni) * 100) 
            : 100;

        // 3. Siswa Izin / Di Luar Sekolah
        $siswaIzinHariIni = DispenSiswa::where('tanggal', $hariIni)
            ->whereIn('status', ['Disetujui', 'Sedang di Luar'])
            ->count();
        $siswaSedangDiLuar = DispenSiswa::where('tanggal', $hariIni)
            ->where('status', 'Sedang di Luar')
            ->count();

        // 4. Monitoring Kondisi Kelas Real-Time
        $kelasBerlangsung = 0;
        $kelasBelumMulai  = 0;
        $guruTerlambat    = 0;

        foreach ($jadwalHariIni as $j) {
            $statusWaktu = $j->statusWaktuMengajar();
            if ($j->sudah_diisi) {
                $kelasBerlangsung++;
            } elseif ($statusWaktu === 'sekarang') {
                $guruTerlambat++;
            } else {
                $kelasBelumMulai++;
            }
        }

        // 5. Persetujuan Izin Guru Menunggu Tanda Tangan Kepala Sekolah (Tahap 3)
        $izinGuruPending = IzinGuru::with('guru')
            ->where('status_waka', 'Disetujui')
            ->where('status_sdm', 'Disetujui')
            ->where('status_kepsek', 'Menunggu')
            ->get();

        // 6. Audit Trail Aktivitas Terakhir
        try {
            $auditLogs = AuditLog::with('user')
                ->orderBy('id', 'desc')
                ->take(8)
                ->get();
        } catch (\Exception $e) {
            $auditLogs = collect();
        }

        return view('kepsek.dashboard', compact(
            'hariIni',
            'namaHari',
            'totalGuru',
            'totalSiswa',
            'totalSesiHariIni',
            'totalJurnalTerisi',
            'persenGuruHadir',
            'siswaIzinHariIni',
            'siswaSedangDiLuar',
            'kelasBerlangsung',
            'kelasBelumMulai',
            'guruTerlambat',
            'izinGuruPending',
            'auditLogs'
        ));
    }

    /**
     * Kepala Sekolah menyetujui izin guru (Persetujuan Tahap Final).
     */
    public function approveIzinGuru($id)
    {
        $izin = IzinGuru::findOrFail($id);
        $izin->update([
            'status_kepsek' => 'Disetujui',
            'status_akhir'  => 'Disetujui',
        ]);

        AuditLog::log(
            'Pengesahan Izin Guru oleh Kepala Sekolah',
            "Kepala Sekolah mengesahkan izin Guru: {$izin->guru->nama_guru} ({$izin->alasan})"
        );

        return back()->with('success', "Izin Guru {$izin->guru->nama_guru} telah berhasil disahkan.");
    }

    /**
     * Kepala Sekolah menolak izin guru.
     */
    public function rejectIzinGuru(Request $request, $id)
    {
        $izin = IzinGuru::findOrFail($id);
        $izin->update([
            'status_kepsek'     => 'Ditolak',
            'status_akhir'      => 'Ditolak',
            'catatan_penolakan' => $request->input('catatan', 'Ditolak oleh Kepala Sekolah'),
        ]);

        AuditLog::log(
            'Penolakan Izin Guru oleh Kepala Sekolah',
            "Kepala Sekolah menolak izin Guru: {$izin->guru->nama_guru}."
        );

        return back()->with('info', "Izin Guru {$izin->guru->nama_guru} telah ditolak.");
    }
}

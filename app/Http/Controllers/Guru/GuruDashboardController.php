<?php

namespace App\Http\Controllers\Guru;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Jadwal;
use App\Models\JurnalMengajar;
use Carbon\Carbon;

class GuruDashboardController
{
    public function index()
    {
        $user  = Auth::user();
        $guru  = $user->guru;

        if (!$guru) {
            abort(403, 'Data guru tidak ditemukan.');
        }

        $hariMap = [
            'Monday'    => 'Senin',    'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',     'Thursday' => 'Kamis',
            'Friday'    => 'Jumat',
        ];
        $namaHariIni  = $hariMap[Carbon::now()->format('l')] ?? 'Senin';
        $tanggalHariIni = Carbon::today()->toDateString();
        $tanggalTeks    = Carbon::now()->locale('id')->isoFormat('dddd, D MMMM Y');

        // Jadwal hari ini milik guru ini
        $jadwalHariIni = Jadwal::with(['kelas', 'mapel', 'ruangan', 'jamMulaiData', 'jamSelesaiData'])
            ->where('id_guru', $guru->id_guru)
            ->where('hari', $namaHariIni)
            ->orderBy('jam_mulai')
            ->get()
            ->map(function ($j) use ($tanggalHariIni) {
                $jurnal = JurnalMengajar::where('id_jadwal', $j->id_jadwal)
                    ->whereDate('tanggal', $tanggalHariIni)
                    ->first();
                $j->jurnal       = $jurnal;
                $j->sudah_diisi  = (bool) $jurnal;
                return $j;
            });

        // Total sesi & sudah diisi hari ini
        $totalSesiHariIni  = $jadwalHariIni->count();
        $sudahDiisiHariIni = $jadwalHariIni->where('sudah_diisi', true)->count();

        // Total jadwal seminggu
        $totalJadwalSemua = Jadwal::where('id_guru', $guru->id_guru)->count();

        // Sesi belum diisi minggu ini
        $belumIsiMingguIni = 0;
        $hariList = ['Senin','Selasa','Rabu','Kamis','Jumat'];
        $startMinggu = Carbon::now()->startOfWeek(Carbon::MONDAY)->toDateString();
        $endMinggu   = Carbon::now()->endOfWeek(Carbon::FRIDAY)->toDateString();

        $jadwalSeminggu = Jadwal::where('id_guru', $guru->id_guru)->get();
        foreach ($jadwalSeminggu as $j) {
            // Cari tanggal hari tersebut di minggu ini
            $dayIndex = array_search($j->hari, $hariList);
            if ($dayIndex === false) continue;
            $tanggalJadwal = Carbon::now()->startOfWeek(Carbon::MONDAY)->addDays($dayIndex)->toDateString();
            if ($tanggalJadwal > Carbon::today()->toDateString()) continue; // skip hari depan
            $ada = JurnalMengajar::where('id_jadwal', $j->id_jadwal)
                ->whereDate('tanggal', $tanggalJadwal)->exists();
            if (!$ada) $belumIsiMingguIni++;
        }

        return view('guru.dashboard', compact(
            'guru',
            'namaHariIni',
            'tanggalTeks',
            'jadwalHariIni',
            'totalSesiHariIni',
            'sudahDiisiHariIni',
            'totalJadwalSemua',
            'belumIsiMingguIni',
        ));
    }
}
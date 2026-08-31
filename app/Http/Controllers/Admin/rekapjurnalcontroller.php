<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Jadwal;
use App\Models\JurnalMengajar;
use Carbon\Carbon;

class RekapJurnalController
{
    public function index(Request $request)
    {
        // 1. Tentukan tanggal filter (default: hari ini)
        $tanggal = $request->input('tanggal', date('Y-m-d'));
        $carbonDate = Carbon::parse($tanggal);

        $hariMap = [
            'Monday'    => 'Senin',
            'Tuesday'   => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday'  => 'Kamis',
            'Friday'    => 'Jumat',
            'Saturday'  => 'Sabtu',
            'Sunday'    => 'Minggu',
        ];
        $namaHari = $hariMap[$carbonDate->format('l')] ?? 'Senin';

        // 2. TABEL A: Jurnal Tersimpan pada Tanggal Tersebut
        $jurnalTersimpan = JurnalMengajar::with(['jadwal.guru', 'jadwal.kelas', 'jadwal.mapel'])
            ->whereDate('tanggal', $tanggal)
            ->get();

        // 3. TABEL B: Laporan Guru Alpa (Jadwal hari itu yang BELUM diisi jurnal)
        $idJadwalTerisi = $jurnalTersimpan->pluck('id_jadwal');

        $guruAlpaList = Jadwal::with(['guru', 'kelas', 'mapel'])
            ->where('hari', $namaHari)
            ->whereNotIn('id_jadwal', $idJadwalTerisi)
            ->orderBy('jam_mulai', 'asc')
            ->get();

        return view('admin.rekap.index', compact('tanggal', 'namaHari', 'jurnalTersimpan', 'guruAlpaList'));
    }
}
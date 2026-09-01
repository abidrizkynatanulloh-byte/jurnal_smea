<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Jadwal;
use App\Models\JurnalMengajar;
use App\Models\JurnalDetailKetidakhadiran;
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

        // Tab aktif (default: terisi)
        $tab = $request->input('tab', 'terisi');

        // 2. TABEL A: Jurnal Tersimpan + Bukti Foto pada Tanggal Tersebut
        $jurnalTersimpan = JurnalMengajar::with([
            'foto',
            'jadwal.guru',
            'jadwal.kelas',
            'jadwal.mapel',
            'jadwal.ruangan',
            'detailKetidakhadiran.siswa'
        ])
        ->whereDate('tanggal', $tanggal)
        ->orderBy('dicatat_pada', 'desc')
        ->get();

        // 3. TABEL B: Detail Ketidakhadiran Siswa (Alpa, Sakit, Izin) pada Tanggal Tersebut
        $siswaAbsenList = JurnalDetailKetidakhadiran::with([
            'siswa.kelas',
            'jurnal.jadwal.guru',
            'jurnal.jadwal.mapel'
        ])
        ->whereHas('jurnal', function ($q) use ($tanggal) {
            $q->whereDate('tanggal', $tanggal);
        })
        ->get();

        // 4. TABEL C: Laporan Guru Belum Isi Jurnal (Alpa Mengajar)
        $idJadwalTerisi = $jurnalTersimpan->pluck('id_jadwal');

        $guruAlpaList = Jadwal::with(['guru', 'kelas', 'mapel', 'ruangan'])
            ->where('hari', $namaHari)
            ->whereNotIn('id_jadwal', $idJadwalTerisi)
            ->orderBy('jam_mulai', 'asc')
            ->get();

        return view('admin.rekap.index', compact(
            'tanggal',
            'namaHari',
            'tab',
            'jurnalTersimpan',
            'siswaAbsenList',
            'guruAlpaList'
        ));
    }

    /**
     * Tampilkan Detail Jurnal Mengajar (Termasuk Foto Bukti & Absensi Siswa)
     */
    public function show($id)
    {
        $jurnal = JurnalMengajar::with([
            'foto',
            'jadwal.guru',
            'jadwal.kelas',
            'jadwal.mapel',
            'jadwal.ruangan',
            'detailKetidakhadiran.siswa'
        ])->findOrFail($id);

        return view('admin.rekap.show', compact('jurnal'));
    }
}
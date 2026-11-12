<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\GuruPiket;
use App\Models\Guru;
use App\Models\AuditLog;
use Carbon\Carbon;

class AdminGuruPiketController
{
    /**
     * Menampilkan daftar penugasan guru piket harian berbasis tanggal (Bulanan).
     */
    public function index(Request $request)
    {
        $bulanSelected = (int) $request->input('bulan', date('n')); // 1-12
        $tahunSelected = (int) $request->input('tahun', date('Y')); // e.g. 2026

        $namaBulanList = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        $startOfMonth = Carbon::createFromDate($tahunSelected, $bulanSelected, 1);
        $daysInMonth = $startOfMonth->daysInMonth;

        $hariMapEnToId = [
            'Monday'    => 'Senin',
            'Tuesday'   => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday'  => 'Kamis',
            'Friday'    => 'Jumat',
            'Saturday'  => 'Sabtu',
            'Sunday'    => 'Minggu',
        ];

        // Buat daftar tanggal kerja (Senin - Jumat) dalam bulan tersebut
        $datesInMonth = [];
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $dateObj = Carbon::createFromDate($tahunSelected, $bulanSelected, $day);
            $dayOfWeekEn = $dateObj->format('l');
            $namaHari = $hariMapEnToId[$dayOfWeekEn] ?? 'Senin';

            // Lewati hari Sabtu & Minggu (5 Hari Kerja)
            if (!in_array($dayOfWeekEn, ['Saturday', 'Sunday'])) {
                $datesInMonth[] = [
                    'tanggal'   => $dateObj->toDateString(),
                    'tgl_num'   => $day,
                    'nama_hari' => $namaHari,
                    'is_today'  => $dateObj->isToday(),
                    'formatted' => $namaHari . ', ' . $day . ' ' . $namaBulanList[$bulanSelected] . ' ' . $tahunSelected,
                    'week_num'  => $dateObj->weekOfMonth,
                ];
            }
        }

        // Group dates by week of month
        $weeksInMonth = [];
        foreach ($datesInMonth as $d) {
            $weeksInMonth[$d['week_num']][] = $d;
        }

        // Ambil penugasan piket pada tanggal_khusus di bulan & tahun terpilih
        $piketRecords = GuruPiket::with('guru')
            ->whereYear('tanggal_khusus', $tahunSelected)
            ->whereMonth('tanggal_khusus', $bulanSelected)
            ->whereNull('deleted_at')
            ->get();

        // Group by tanggal_khusus
        $piketPerTanggal = [];
        foreach ($piketRecords as $p) {
            $piketPerTanggal[$p->tanggal_khusus][] = $p;
        }

        // Daftar semua guru aktif untuk dropdown penugasan
        $guruList = Guru::orderBy('nama_guru', 'asc')->get();

        return view('admin.guru-piket.index', compact(
            'bulanSelected',
            'tahunSelected',
            'namaBulanList',
            'datesInMonth',
            'weeksInMonth',
            'piketPerTanggal',
            'guruList'
        ));
    }

    /**
     * Menambahkan penugasan guru piket per tanggal spesifik, shift & peran.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_guru'        => 'required|exists:guru,id_guru',
            'tanggal_khusus' => 'required|date',
            'shift'          => 'required|in:Pagi,Siang',
            'peran_piket'    => 'required|in:Petugas,Koordinator,Piket Waka',
            'keterangan'     => 'nullable|string|max:255',
        ]);

        $carbonDate = Carbon::parse($request->tanggal_khusus);
        if ($carbonDate->isWeekend()) {
            return back()->with('error', 'Tidak dapat menambahkan penugasan piket pada hari libur (Sabtu & Minggu).');
        }

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

        // Cek apakah guru sudah terdaftar piket pada tanggal & shift tersebut
        $exists = GuruPiket::where('id_guru', $request->id_guru)
            ->where('tanggal_khusus', $request->tanggal_khusus)
            ->where('shift', $request->shift)
            ->whereNull('deleted_at')
            ->exists();

        if ($exists) {
            return back()->with('error', 'Guru tersebut sudah terdaftar sebagai Guru Piket pada tanggal ' . $carbonDate->format('d/m/Y') . ' (Shift ' . $request->shift . ').');
        }

        $guruPiket = GuruPiket::create([
            'id_guru'        => $request->id_guru,
            'hari'           => $namaHari,
            'tanggal_khusus' => $request->tanggal_khusus,
            'shift'          => $request->shift,
            'peran_piket'    => $request->peran_piket,
            'keterangan'     => $request->keterangan,
        ]);

        // Otomatis sinkronkan role user menjadi guru_piket agar guru bisa langsung piket & tetap mengajar
        $userGuru = \App\Models\User::where('id_guru', $request->id_guru)->first();
        if ($userGuru && in_array($userGuru->role, ['guru'])) {
            $userGuru->update(['role' => 'guru_piket']);
        }

        // Sinkronkan jabatan guru di tabel guru menjadi Guru Piket
        $guru = Guru::find($request->id_guru);
        if ($guru && in_array($guru->jabatan, ['Guru', null, ''])) {
            $guru->update(['jabatan' => 'Guru Piket']);
        }
        $namaGuru = $guru ? $guru->nama_guru : 'Guru';
        $tglFormatted = $carbonDate->format('d/m/Y');

        if (class_exists(AuditLog::class)) {
            AuditLog::log(
                'Tambah Guru Piket',
                "Menugaskan {$namaGuru} sebagai {$request->peran_piket} (Shift {$request->shift}) tanggal {$tglFormatted} ({$namaHari})."
            );
        }

        return back()->with('success', "Berhasil menugaskan {$namaGuru} sebagai {$request->peran_piket} (Shift {$request->shift}) pada tanggal {$tglFormatted}.");
    }

    /**
     * Menghapus penugasan guru piket.
     */
    public function destroy($id)
    {
        $guruPiket = GuruPiket::with('guru')->findOrFail($id);
        $idGuru = $guruPiket->id_guru;
        $namaGuru = $guruPiket->guru ? $guruPiket->guru->nama_guru : 'Guru';
        $tglStr = $guruPiket->tanggal_khusus ? Carbon::parse($guruPiket->tanggal_khusus)->format('d/m/Y') : $guruPiket->hari;
        $shift = $guruPiket->shift;

        $guruPiket->delete();

        // Cek jika guru sudah tidak memiliki jadwal piket aktif lainnya sama sekali
        $sisaPiket = GuruPiket::where('id_guru', $idGuru)->whereNull('deleted_at')->exists();
        if (!$sisaPiket) {
            $userGuru = \App\Models\User::where('id_guru', $idGuru)->first();
            if ($userGuru && $userGuru->role === 'guru_piket') {
                $userGuru->update(['role' => 'guru']);
            }
            $guruModel = Guru::find($idGuru);
            if ($guruModel && $guruModel->jabatan === 'Guru Piket') {
                $guruModel->update(['jabatan' => 'Guru']);
            }
        }

        if (class_exists(AuditLog::class)) {
            AuditLog::log(
                'Hapus Guru Piket',
                "Menghapus penugasan Guru Piket {$namaGuru} (Shift {$shift}) tanggal/hari {$tglStr}."
            );
        }

        return back()->with('success', "Penugasan Guru Piket {$namaGuru} (Shift {$shift}) pada {$tglStr} berhasil dihapus.");
    }
}

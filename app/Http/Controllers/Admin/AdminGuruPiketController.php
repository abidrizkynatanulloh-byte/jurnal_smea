<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\GuruPiket;
use App\Models\Guru;
use App\Models\AuditLog;

class AdminGuruPiketController
{
    /**
     * Menampilkan daftar penugasan guru piket harian (Senin - Jumat) per shift & peran.
     */
    public function index()
    {
        $hariList = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];

        // Ambil penugasan guru piket per hari
        $piketPerHari = [];
        foreach ($hariList as $hari) {
            $piketPerHari[$hari] = GuruPiket::with('guru')
                ->where('hari', $hari)
                ->whereNull('deleted_at')
                ->get();
        }

        // Daftar semua guru aktif untuk dropdown penugasan
        $guruList = Guru::orderBy('nama_guru', 'asc')->get();
        $daftarGuru = $guruList;

        return view('admin.guru-piket.index', compact('hariList', 'piketPerHari', 'guruList', 'daftarGuru'));
    }

    /**
     * Menambahkan penugasan guru piket permanen per hari, shift & peran.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_guru'     => 'required|exists:guru,id_guru',
            'hari'        => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat',
            'shift'       => 'required|in:Pagi,Siang',
            'peran_piket'  => 'required|in:Petugas,Koordinator,Piket Waka',
            'keterangan'  => 'nullable|string|max:255',
        ]);

        // Cek apakah guru sudah terdaftar piket pada hari & shift tersebut
        $exists = GuruPiket::where('id_guru', $request->id_guru)
            ->where('hari', $request->hari)
            ->where('shift', $request->shift)
            ->whereNull('deleted_at')
            ->exists();

        if ($exists) {
            return back()->with('error', 'Guru tersebut sudah terdaftar sebagai Guru Piket pada hari ' . $request->hari . ' (Shift ' . $request->shift . ').');
        }

        $guruPiket = GuruPiket::create([
            'id_guru'     => $request->id_guru,
            'hari'        => $request->hari,
            'shift'       => $request->shift,
            'peran_piket'  => $request->peran_piket,
            'keterangan'  => $request->keterangan,
        ]);

        $guru = Guru::find($request->id_guru);
        $namaGuru = $guru ? $guru->nama_guru : 'Guru';

        if (class_exists(AuditLog::class)) {
            AuditLog::log(
                'Tambah Guru Piket',
                "Menugaskan {$namaGuru} sebagai {$request->peran_piket} (Shift {$request->shift}) hari {$request->hari}."
            );
        }

        return back()->with('success', "Berhasil menugaskan {$namaGuru} sebagai {$request->peran_piket} (Shift {$request->shift}) hari {$request->hari}.");
    }

    /**
     * Menghapus penugasan guru piket.
     */
    public function destroy($id)
    {
        $guruPiket = GuruPiket::with('guru')->findOrFail($id);
        $namaGuru = $guruPiket->guru ? $guruPiket->guru->nama_guru : 'Guru';
        $hari = $guruPiket->hari;
        $shift = $guruPiket->shift;

        $guruPiket->delete();

        if (class_exists(AuditLog::class)) {
            AuditLog::log(
                'Hapus Guru Piket',
                "Menghapus penugasan Guru Piket {$namaGuru} (Shift {$shift}) di hari {$hari}."
            );
        }

        return back()->with('success', "Penugasan Guru Piket {$namaGuru} (Shift {$shift}) di hari {$hari} berhasil dihapus.");
    }
}

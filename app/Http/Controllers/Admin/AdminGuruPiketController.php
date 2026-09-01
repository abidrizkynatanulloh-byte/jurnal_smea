<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\GuruPiket;
use App\Models\Guru;
use App\Models\AuditLog;

class AdminGuruPiketController
{
    /**
     * Menampilkan daftar penugasan guru piket harian (Senin - Jumat).
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
        $daftarGuru = Guru::orderBy('nama_guru', 'asc')->get();

        return view('admin.guru-piket.index', compact('hariList', 'piketPerHari', 'daftarGuru'));
    }

    /**
     * Menambahkan penugasan guru piket permanen per hari.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_guru' => 'required|exists:guru,id_guru',
            'hari'    => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat',
        ]);

        // Cek apakah guru sudah terdaftar piket pada hari tersebut
        $exists = GuruPiket::where('id_guru', $request->id_guru)
            ->where('hari', $request->hari)
            ->whereNull('deleted_at')
            ->exists();

        if ($exists) {
            return back()->with('error', 'Guru tersebut sudah terdaftar sebagai Guru Piket pada hari ' . $request->hari . '.');
        }

        $guruPiket = GuruPiket::create([
            'id_guru' => $request->id_guru,
            'hari'    => $request->hari,
        ]);

        $guru = Guru::find($request->id_guru);
        $namaGuru = $guru ? $guru->nama_guru : 'Guru';

        if (class_exists(AuditLog::class)) {
            AuditLog::log(
                'Tambah Guru Piket',
                "Menugaskan {$namaGuru} sebagai Guru Piket hari {$request->hari}."
            );
        }

        return back()->with('success', "Berhasil menugaskan {$namaGuru} sebagai Guru Piket hari {$request->hari}.");
    }

    /**
     * Menghapus penugasan guru piket.
     */
    public function destroy($id)
    {
        $guruPiket = GuruPiket::with('guru')->findOrFail($id);
        $namaGuru = $guruPiket->guru ? $guruPiket->guru->nama_guru : 'Guru';
        $hari = $guruPiket->hari;

        $guruPiket->delete();

        if (class_exists(AuditLog::class)) {
            AuditLog::log(
                'Hapus Guru Piket',
                "Menghapus penugasan Guru Piket {$namaGuru} di hari {$hari}."
            );
        }

        return back()->with('success', "Penugasan Guru Piket {$namaGuru} di hari {$hari} berhasil dihapus.");
    }
}

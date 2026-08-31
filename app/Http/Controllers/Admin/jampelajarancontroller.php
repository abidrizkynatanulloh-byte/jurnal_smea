<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\JamPelajaran;

class JamPelajaranController
{
    /**
     * Menampilkan Halaman Master Jam Pelajaran (Form Tambah Jam di Atas + Tabel di Bawah).
     */
    public function index()
    {
        $jamList = JamPelajaran::orderBy('jam_ke', 'asc')->get();
        return view('admin.jam_pelajaran.index', compact('jamList'));
    }

    /**
     * Menambah Jam Sesi Baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'jam_ke'        => 'required|integer|min:1|unique:jam_pelajaran,jam_ke',
            'waktu_mulai'   => 'required',
            'waktu_selesai' => 'required|after:waktu_mulai',
        ], [
            'jam_ke.required'        => 'Jam ke- wajib diisi.',
            'jam_ke.unique'          => 'Sesi Jam ke- ini sudah ada.',
            'waktu_mulai.required'   => 'Waktu mulai wajib diisi.',
            'waktu_selesai.required' => 'Waktu selesai wajib diisi.',
            'waktu_selesai.after'    => 'Waktu selesai harus lebih besar dari waktu mulai.',
        ]);

        JamPelajaran::create($validated);

        return redirect()->route('admin.jam.index')->with('success', 'Sesi jam pelajaran berhasil ditambahkan!');
    }

    /**
     * Update/Simpan Perubahan Waktu Sesi Jam (Tombol "SIMPAN PERUBAHAN" per Baris).
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'waktu_mulai'   => 'required',
            'waktu_selesai' => 'required',
        ]);

        $jam = JamPelajaran::findOrFail($id);
        $jam->update($validated);

        return redirect()->route('admin.jam.index')->with('success', 'Waktu Jam ke-' . $jam->jam_ke . ' berhasil diperbarui!');
    }

    /**
     * Hapus Sesi Jam Pelajaran.
     */
    public function destroy($id)
    {
        JamPelajaran::findOrFail($id)->delete();
        return redirect()->route('admin.jam.index')->with('success', 'Sesi jam pelajaran berhasil dihapus.');
    }
}
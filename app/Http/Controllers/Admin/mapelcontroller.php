<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Mapel;

class MapelController
{
    /**
     * Menampilkan Halaman Mata Pelajaran (Form Tambah Mapel di Atas + Tabel di Bawah).
     */
    public function index(Request $request)
    {
        $query = Mapel::query();

        if ($request->filled('search')) {
            $keyword = trim($request->search);
            $query->where('nama_mapel', 'LIKE', "%{$keyword}%")
                  ->orWhere('kode_mapel', 'LIKE', "%{$keyword}%");
        }

        $mapelList = $query->orderBy('kode_mapel')->paginate(10)->withQueryString();
        $totalMapel = Mapel::count();

        return view('admin.mapel.index', compact('mapelList', 'totalMapel'));
    }

    /**
     * Menyimpan Mata Pelajaran Baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_mapel' => 'required|string|max:10|unique:mapel,kode_mapel',
            'nama_mapel' => 'required|string|max:150',
        ], [
            'kode_mapel.required' => 'Kode mapel wajib diisi.',
            'kode_mapel.unique'   => 'Kode mapel ini sudah digunakan.',
            'nama_mapel.required' => 'Nama mata pelajaran wajib diisi.',
        ]);

        Mapel::create($validated);

        return redirect()->route('admin.mapel.index')->with('success', 'Mata pelajaran berhasil ditambahkan!');
    }

    /**
     * Soft Delete Mapel.
     */
    public function destroy($kode)
    {
        Mapel::findOrFail($kode)->delete();
        return redirect()->route('admin.mapel.index')->with('success', 'Mata pelajaran berhasil dihapus.');
    }
}
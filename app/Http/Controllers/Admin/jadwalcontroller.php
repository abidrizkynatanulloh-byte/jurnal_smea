<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\Guru;
use App\Models\Mapel;
use App\Models\Ruangan;

class JadwalController
{
    public function index(Request $request)
    {
        $kelasList = Kelas::orderBy('nama_kelas')->get();
        $guruList = Guru::orderBy('nama_guru')->get();
        $mapelList = Mapel::orderBy('nama_mapel')->get();
        $ruanganList = Ruangan::orderBy('nama_ruangan')->get();

        $query = Jadwal::with(['kelas', 'guru', 'mapel', 'ruangan']);

        if ($request->filled('hari')) {
            $query->where('hari', $request->hari);
        }
        if ($request->filled('id_kelas')) {
            $query->where('id_kelas', $request->id_kelas);
        }

        $jadwalList = $query->orderBy('hari')->orderBy('jam_mulai')->paginate(10)->withQueryString();

        return view('admin.jadwal.index', compact('jadwalList', 'kelasList', 'guruList', 'mapelList', 'ruanganList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_kelas'    => 'required|exists:kelas,id_kelas',
            'id_guru'     => 'required|exists:guru,id_guru',
            'id_ruangan'  => 'required|exists:ruangan,id_ruangan',
            'kode_mapel'  => 'required|exists:mapel,kode_mapel',
            'hari'        => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat',
            'jam_mulai'   => 'required|integer|min:1|max:15',
            'jam_selesai' => 'required|integer|gte:jam_mulai|max:15',
        ]);

        Jadwal::create($validated);

        return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal pelajaran berhasil ditambahkan!');
    }

    public function destroy($id)
    {
        Jadwal::findOrFail($id)->delete();
        return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal berhasil dihapus.');
    }
}
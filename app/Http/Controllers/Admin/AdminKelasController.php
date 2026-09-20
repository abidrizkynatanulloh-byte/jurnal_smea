<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Kelas;
use App\Models\Guru;
use App\Models\Siswa;
use App\Models\Jadwal;

class AdminKelasController
{
    /**
     * Tampilkan seluruh data kelas beserta statistik siswa, jadwal, dan wali kelas.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $tingkat = $request->input('tingkat');

        $query = Kelas::withCount(['siswa', 'jadwal']);

        if (!empty($search)) {
            $query->where('nama_kelas', 'like', '%' . $search . '%');
        }

        if (!empty($tingkat)) {
            $query->where(function($q) use ($tingkat) {
                $q->where('nama_kelas', 'like', $tingkat . ' %')
                  ->orWhere('nama_kelas', 'like', $tingkat . '-%')
                  ->orWhere('nama_kelas', 'like', $tingkat . '.%');
            });
        }

        $daftarKelas = $query->orderBy('nama_kelas', 'asc')->get();
        $daftarGuru  = Guru::orderBy('nama_guru', 'asc')->get();

        // Hitung total ringkasan
        $totalKelas = $daftarKelas->count();
        $totalSiswa = Siswa::count();

        return view('admin.kelas.index', compact('daftarKelas', 'daftarGuru', 'totalKelas', 'totalSiswa', 'search', 'tingkat'));
    }

    /**
     * Ambil data daftar siswa untuk suatu kelas (API/JSON untuk modal detail).
     */
    public function getSiswa($id)
    {
        $kelas = Kelas::findOrFail($id);
        $siswaList = Siswa::where('id_kelas', $id)->orderBy('nama_siswa', 'asc')->get();

        $wali = null;
        if ($kelas->wali_kelas) {
            $g = Guru::where('nip', $kelas->wali_kelas)->orWhere('id_guru', $kelas->wali_kelas)->first();
            $wali = $g ? $g->nama_guru : $kelas->wali_kelas;
        }

        return response()->json([
            'kelas'      => $kelas,
            'wali_kelas' => $wali,
            'total'      => $siswaList->count(),
            'siswa'      => $siswaList,
        ]);
    }

    /**
     * Tambah data kelas baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_kelas' => 'required|string|max:50|unique:kelas,nama_kelas',
            'id_guru'    => 'nullable|exists:guru,id_guru',
        ], [
            'nama_kelas.required' => 'Nama kelas wajib diisi.',
            'nama_kelas.unique'   => 'Nama kelas tersebut sudah ada.',
        ]);

        $waliVal = null;
        if ($request->filled('id_guru')) {
            $guru = Guru::findOrFail($request->id_guru);
            $waliVal = $guru->nip ?: $guru->id_guru;
        }

        Kelas::create([
            'nama_kelas' => trim($request->nama_kelas),
            'wali_kelas' => $waliVal,
        ]);

        return redirect()->route('admin.kelas.index')->with('success', 'Kelas baru berhasil ditambahkan!');
    }

    /**
     * Perbarui data kelas & penugasan wali kelas.
     */
    public function update(Request $request, $id)
    {
        $kelas = Kelas::findOrFail($id);

        $request->validate([
            'nama_kelas' => 'required|string|max:50|unique:kelas,nama_kelas,' . $id . ',id_kelas',
            'id_guru'    => 'nullable',
        ]);

        $waliVal = null;
        if ($request->filled('id_guru')) {
            $guru = Guru::find($request->id_guru);
            if ($guru) {
                $waliVal = $guru->nip ?: $guru->id_guru;
            }
        }

        $kelas->update([
            'nama_kelas' => trim($request->nama_kelas),
            'wali_kelas' => $waliVal,
        ]);

        return redirect()->route('admin.kelas.index')->with('success', "Data kelas {$kelas->nama_kelas} berhasil diperbarui!");
    }

    /**
     * Hapus kelas jika tidak memiliki siswa atau jadwal terkait.
     */
    public function destroy($id)
    {
        $kelas = Kelas::findOrFail($id);

        if ($kelas->siswa()->count() > 0) {
            return back()->with('error', "Kelas {$kelas->nama_kelas} tidak dapat dihapus karena masih memiliki data siswa.");
        }

        $nama = $kelas->nama_kelas;
        $kelas->delete();

        return redirect()->route('admin.kelas.index')->with('success', "Kelas {$nama} berhasil dihapus.");
    }
}

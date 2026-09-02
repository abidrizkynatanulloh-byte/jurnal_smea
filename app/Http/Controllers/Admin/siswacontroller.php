<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SiswaController
{
    /**
     * Menampilkan Halaman Data Siswa (Form Tambah Siswa di Atas + Tabel di Bawah).
     */
    public function index(Request $request)
    {
        // 1. Data Kelas untuk Dropdown Pilihan
        $kelasList = Kelas::orderBy('nama_kelas')->get();

        // 2. Query Siswa dengan Search & Filter Kelas
        $query = Siswa::with('kelas');

        if ($request->filled('search')) {
            $keyword = trim($request->search);
            $query->where(function ($q) use ($keyword) {
                $q->where('nama_siswa', 'LIKE', "%{$keyword}%")
                  ->orWhere('nis', 'LIKE', "%{$keyword}%")
                  ->orWhere('nisn', 'LIKE', "%{$keyword}%");
            });
        }

        if ($request->filled('id_kelas')) {
            $query->where('id_kelas', $request->id_kelas);
        }

        $perPage = (int) $request->input('per_page', 30);
        if ($perPage <= 0) $perPage = 30;

        $siswaList = $query->orderBy('nama_siswa')->paginate($perPage)->withQueryString();
        $totalSiswa = Siswa::count();

        return view('admin.siswa.index', compact('siswaList', 'kelasList', 'totalSiswa'));
    }

    /**
     * Menyimpan Siswa Baru + Otomatis Buat Akun Login untuk Wali Murid.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nis'           => 'required|string|max:10|unique:siswa,nis',
            'nisn'          => 'required|string|max:20|unique:siswa,nisn|unique:users,username',
            'nama_siswa'    => 'required|string|max:100',
            'id_kelas'      => 'required|exists:kelas,id_kelas',
            'jenis_kelamin' => 'nullable|in:L,P',
            'no_hp_wali'    => 'nullable|string|max:25',
        ], [
            'nis.required'        => 'Nomor Induk Siswa (NIS) wajib diisi.',
            'nis.unique'          => 'NIS ini sudah terdaftar.',
            'nisn.required'       => 'NISN wajib diisi.',
            'nisn.unique'         => 'NISN ini sudah terdaftar.',
            'nama_siswa.required' => 'Nama lengkap siswa wajib diisi.',
            'id_kelas.required'   => 'Pilih kelas siswa.',
        ]);

        DB::beginTransaction();
        try {
            $dataToSave = [
                'nis'           => $validated['nis'],
                'nisn'          => $validated['nisn'],
                'nama_siswa'    => $validated['nama_siswa'],
                'id_kelas'      => $validated['id_kelas'],
                'jenis_kelamin' => $validated['jenis_kelamin'] ?? 'L',
                'no_hp_wali'    => $validated['no_hp_wali'] ?? null,
            ];

            if (!Schema::hasColumn('siswa', 'jenis_kelamin')) unset($dataToSave['jenis_kelamin']);
            if (!Schema::hasColumn('siswa', 'no_hp_wali')) unset($dataToSave['no_hp_wali']);

            // 1. Simpan profil siswa
            $siswa = Siswa::create($dataToSave);

            // 2. Buat akun login Wali Murid otomatis (Password default: wali123)
            User::updateOrCreate(
                ['username' => $validated['nisn']],
                [
                    'password'   => Hash::make('wali123'),
                    'role'       => 'wali_murid',
                    'nisn_siswa' => $validated['nisn'],
                    'is_active'  => 1,
                ]
            );

            DB::commit();
            return redirect()->route('admin.siswa.index')->with('success', 'Siswa baru berhasil ditambahkan dan akun Wali Murid aktif!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Gagal: ' . $e->getMessage()]);
        }
    }

    /**
     * Memperbarui Data Siswa.
     */
    public function update(Request $request, $nis)
    {
        $siswa = Siswa::where('nis', $nis)->firstOrFail();

        $validated = $request->validate([
            'nama_siswa'    => 'required|string|max:100',
            'nisn'          => 'required|string|max:20|unique:siswa,nisn,' . $siswa->nis . ',nis',
            'id_kelas'      => 'required|exists:kelas,id_kelas',
            'jenis_kelamin' => 'nullable|in:L,P',
            'no_hp_wali'    => 'nullable|string|max:25',
        ]);

        $updateData = [
            'nama_siswa' => $validated['nama_siswa'],
            'nisn'       => $validated['nisn'],
            'id_kelas'   => $validated['id_kelas'],
        ];

        if (Schema::hasColumn('siswa', 'jenis_kelamin') && isset($validated['jenis_kelamin'])) {
            $updateData['jenis_kelamin'] = $validated['jenis_kelamin'];
        }
        if (Schema::hasColumn('siswa', 'no_hp_wali')) {
            $updateData['no_hp_wali'] = $validated['no_hp_wali'] ?? null;
        }

        $siswa->update($updateData);

        return redirect()->route('admin.siswa.index')->with('success', 'Data siswa berhasil diperbarui!');
    }

    /**
     * Soft Delete Data Siswa.
     */
    public function destroy($nis)
    {
        $siswa = Siswa::where('nis', $nis)->firstOrFail();
        $siswa->delete();

        return redirect()->route('admin.siswa.index')->with('success', 'Data siswa berhasil dipindahkan ke sampah.');
    }

    /**
     * Menampilkan Data Siswa yang Ada di Sampah (Trash).
     */
    public function trash()
    {
        $trashSiswa = Siswa::onlyTrashed()->with('kelas')->paginate(30);
        return view('admin.siswa.trash', compact('trashSiswa'));
    }

    /**
     * Memulihkan Data Siswa dari Sampah (Restore).
     */
    public function restore($nis)
    {
        $siswa = Siswa::onlyTrashed()->where('nis', $nis)->firstOrFail();
        $siswa->restore();

        return redirect()->route('admin.siswa.trash')->with('success', 'Data siswa berhasil dipulihkan!');
    }
}
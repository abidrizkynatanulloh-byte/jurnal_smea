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

        // 2. Query Siswa dengan Search
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

        $siswaList = $query->orderBy('nama_siswa')->paginate(15)->withQueryString();
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
            'kota_lahir'    => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|date',
            'alamat'        => 'nullable|string',
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
            // Filter hanya kolom yang benar-benar ada di tabel agar aman
            $dataToSave = $validated;
            if (!Schema::hasColumn('siswa', 'jenis_kelamin')) {
                unset($dataToSave['jenis_kelamin']);
            }
            if (!Schema::hasColumn('siswa', 'no_hp_wali')) {
                unset($dataToSave['no_hp_wali']);
            }

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
        $siswa = Siswa::findOrFail($nis);

        $validated = $request->validate([
            'nama_siswa'    => 'required|string|max:100',
            'id_kelas'      => 'nullable|exists:kelas,id_kelas',
            'jenis_kelamin' => 'nullable|in:L,P',
            'no_hp_wali'    => 'nullable|string|max:25',
            'kota_lahir'    => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|date',
            'alamat'        => 'nullable|string',
        ], [
            'nama_siswa.required' => 'Nama lengkap siswa wajib diisi.',
        ]);

        // Filter kolom yang ada di database agar tidak terjadi SQL Column Not Found
        $dataToUpdate = $validated;
        if (!Schema::hasColumn('siswa', 'jenis_kelamin')) {
            unset($dataToUpdate['jenis_kelamin']);
        }
        if (!Schema::hasColumn('siswa', 'no_hp_wali')) {
            unset($dataToUpdate['no_hp_wali']);
        }

        $siswa->update($dataToUpdate);

        return redirect()->route('admin.siswa.index')->with('success', "Data siswa {$siswa->nama_siswa} berhasil diperbarui.");
    }

    /**
     * Soft Delete Data Siswa.
     */
    public function destroy($nis)
    {
        $siswa = Siswa::findOrFail($nis);
        User::where('nisn_siswa', $siswa->nisn)->delete();
        $siswa->delete();

        return redirect()->route('admin.siswa.index')->with('success', 'Data siswa berhasil dihapus (Soft Delete).');
    }

    /**
     * Tong Sampah Siswa.
     */
    public function trash()
    {
        $trashList = Siswa::onlyTrashed()->paginate(15);
        return view('admin.siswa.trash', compact('trashList'));
    }

    public function restore($nis)
    {
        $siswa = Siswa::onlyTrashed()->findOrFail($nis);
        $siswa->restore();
        User::withTrashed()->where('nisn_siswa', $siswa->nisn)->restore();

        return redirect()->route('admin.siswa.trash')->with('success', 'Data siswa berhasil dipulihkan!');
    }
}
<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\StafTu;
use App\Models\Satpam;
use App\Models\Guru;
use App\Models\Siswa;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserController
{
    /**
     * Menampilkan Form Tambah User + Tabel Daftar User dengan FITUR SEARCH.
     */
    public function index(Request $request)
    {
        // 1. Pilihan Role untuk Form
        $roles = [
            'staf_tu'        => 'Staf TU / Admin',
            'satpam'         => 'Satpam',
            'guru'           => 'Guru Mata Pelajaran',
            'guru_piket'     => 'Guru Piket',
            'kepala_sekolah' => 'Kepala Sekolah',
            'wakasis_siswa'  => 'Wakil Kesiswaan (Siswa)',
            'wakasis_guru'   => 'Wakil Kesiswaan (Guru)',
            'wali_murid'     => 'Wali Murid / Orang Tua',
        ];

        // 2. Query Data User dengan Fitur Search
        $query = User::with(['guru', 'stafTu', 'satpam', 'siswa']);

        // Jika kolom pencarian diisi oleh admin
        if ($request->filled('search')) {
            $keyword = trim($request->search);

            $query->where(function ($q) use ($keyword) {
                // Cari berdasarkan Username / NIP / NISN
                $q->where('username', 'LIKE', "%{$keyword}%")
                  // Atau cari berdasarkan Role
                  ->orWhere('role', 'LIKE', "%{$keyword}%")
                  // Atau cari berdasarkan Nama Guru
                  ->orWhereHas('guru', function ($g) use ($keyword) {
                      $g->where('nama_guru', 'LIKE', "%{$keyword}%");
                  })
                  // Atau cari berdasarkan Nama Siswa
                  ->orWhereHas('siswa', function ($s) use ($keyword) {
                      $s->where('nama_siswa', 'LIKE', "%{$keyword}%");
                  })
                  // Atau cari berdasarkan Nama Satpam
                  ->orWhereHas('satpam', function ($sat) use ($keyword) {
                      $sat->where('nama_satpam', 'LIKE', "%{$keyword}%");
                  })
                  // Atau cari berdasarkan Nama Staf TU
                  ->orWhereHas('stafTu', function ($stf) use ($keyword) {
                      $stf->where('nama_staf', 'LIKE', "%{$keyword}%");
                  });
            });
        }

        $perPage = (int) $request->input('per_page', 30);
        if ($perPage <= 0) $perPage = 30;

        // Pagination data per halaman (mempertahankan kata kunci search saat ganti halaman)
        $users = $query->latest()->paginate($perPage)->withQueryString();

        return view('admin.users.index', compact('roles', 'users'));
    }

    /**
     * Menyimpan User Baru ke Database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'role'     => 'required|in:staf_tu,satpam,guru,guru_piket,kepala_sekolah,wakasis_siswa,wakasis_guru,wali_murid',
            'nama'     => 'required|string|max:150',
            'username' => 'required|string|max:50|unique:users,username',
            'password' => 'required|string|min:4',
            'no_hp'    => 'nullable|string|max:15',
        ], [
            'role.required'     => 'Role wajib dipilih.',
            'nama.required'     => 'Nama lengkap wajib diisi.',
            'username.required' => 'NIP / NISN / Username wajib diisi.',
            'username.unique'   => 'Username ini sudah terdaftar di sistem.',
            'password.required' => 'Password wajib diisi.',
            'password.min'      => 'Password minimal 4 karakter.',
        ]);

        DB::beginTransaction();

        try {
            $idGuru = null;
            $idStaf = null;
            $idSatpam = null;
            $nisnSiswa = null;

            if ($validated['role'] === 'staf_tu') {
                $staf = StafTu::create([
                    'nip'       => $validated['username'],
                    'nama_staf' => $validated['nama'],
                    'no_hp'     => $validated['no_hp'],
                    'jabatan'   => 'Staf TU',
                ]);
                $idStaf = $staf->id_staf;

            } elseif ($validated['role'] === 'satpam') {
                $satpam = Satpam::create([
                    'usn'         => $validated['username'],
                    'nama_satpam' => $validated['nama'],
                    'no_hp'       => $validated['no_hp'],
                ]);
                $idSatpam = $satpam->id_satpam;

            } elseif (in_array($validated['role'], ['guru', 'guru_piket', 'kepala_sekolah', 'wakasis_siswa', 'wakasis_guru'])) {
                $guru = Guru::firstOrCreate(
                    ['nip' => $validated['username']],
                    [
                        'nama_guru' => $validated['nama'],
                        'no_hp'     => $validated['no_hp'],
                    ]
                );
                $idGuru = $guru->id_guru;

            } elseif ($validated['role'] === 'wali_murid') {
                $nisnSiswa = $validated['username'];
            }

            User::create([
                'username'   => $validated['username'],
                'password'   => Hash::make($validated['password']),
                'role'       => $validated['role'],
                'id_guru'    => $idGuru,
                'id_staf'    => $idStaf,
                'id_satpam'  => $idSatpam,
                'nisn_siswa' => $nisnSiswa,
                'is_active'  => 1,
            ]);

            DB::commit();

            return redirect()->route('admin.users.index')->with('success', 'User baru berhasil ditambahkan!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Gagal menyimpan user: ' . $e->getMessage()]);
        }
    }

    /**
     * Menampilkan Form Edit User (Username, Password, Role).
     */
    public function edit($id)
    {
        $user = User::with(['guru', 'stafTu', 'satpam', 'siswa'])->findOrFail($id);

        $roles = [
            'staf_tu'        => 'Staf TU / Admin',
            'satpam'         => 'Satpam',
            'guru'           => 'Guru Mata Pelajaran',
            'guru_piket'     => 'Guru Piket',
            'kepala_sekolah' => 'Kepala Sekolah',
            'wakasis_siswa'  => 'Wakil Kesiswaan (Siswa)',
            'wakasis_guru'   => 'Wakil Kesiswaan (Guru)',
            'wali_murid'     => 'Wali Murid / Orang Tua',
        ];

        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Menyimpan Perubahan Akun User.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // 1. Validasi Input Edit
        $validated = $request->validate([
            'username'  => 'required|string|max:50|unique:users,username,' . $user->id, // Username unik kecuali untuk user ini sendiri
            'role'      => 'required|in:staf_tu,satpam,guru,guru_piket,kepala_sekolah,wakasis_siswa,wakasis_guru,wali_murid',
            'password'  => 'nullable|string|min:4', // Password opsional (hanya diisi jika mau ganti password)
            'is_active' => 'required|boolean',
        ], [
            'username.required' => 'Username / NIP / NISN wajib diisi.',
            'username.unique'   => 'Username ini sudah digunakan akun lain.',
            'password.min'      => 'Password baru minimal 4 karakter.',
        ]);

        // 2. Data yang akan di-update
        $updateData = [
            'username'  => $validated['username'],
            'role'      => $validated['role'],
            'is_active' => $validated['is_active'],
        ];

        // 3. Jika password baru diisi, enkripsi dan simpan
        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $user->update($updateData);

        return redirect()->route('admin.users.index')->with('success', 'Akun pengguna ' . $user->username . ' berhasil diperbarui!');
    }

    /**
     * Menghapus Akun User (SOFT DELETE - Data tidak hilang permanen).
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // Mencegah admin menghapus akunnya sendiri yang sedang login
        if ($user->id === auth()->id()) {
            return back()->withErrors(['error' => 'Anda tidak dapat menghapus akun Anda sendiri yang sedang aktif digunakan.']);
        }

        // Soft Delete (hanya mengisi kolom deleted_at di database)
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'Akun ' . $user->username . ' berhasil dinonaktifkan / dihapus (Soft Delete).');
    }
}
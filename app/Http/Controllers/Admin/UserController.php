<?php

namespace App\Http\Controllers\Admin;

// Mengimpor kelas Request bawaan Laravel
use Illuminate\Http\Request;
// Mengimpor Model-Model yang dibutuhkan
use App\Models\User;
use App\Models\StafTu;
use App\Models\Satpam;
use App\Models\Guru;
use App\Models\Siswa;
// Mengimpor Hash untuk enkripsi password
use Illuminate\Support\Facades\Hash;
// Mengimpor DB Transaction untuk keamanan data
use Illuminate\Support\Facades\DB;

class UserController
{
    /**
     * Menampilkan 1 halaman yang berisi FORM TAMBAH USER + TABEL DAFTAR USER.
     */
    public function index()
    {
        // 1. Data daftar role untuk dropdown form tambah user
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

        // 2. Data daftar user untuk tabel di bawah form
        $users = User::with(['guru', 'stafTu', 'satpam', 'siswa'])
                    ->latest()
                    ->paginate(10);

        // Mengirim data $roles dan $users ke 1 file view
        return view('admin.users.index', compact('roles', 'users'));
    }

    /**
     * Memproses penyimpanan user baru.
     */
    public function store(Request $request)
    {
        // 1. Validasi Input Form
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
            'username.unique'   => 'Username / NIP / NISN ini sudah terdaftar di sistem.',
            'password.required' => 'Password wajib diisi.',
            'password.min'      => 'Password minimal 4 karakter.',
        ]);

        DB::beginTransaction();

        try {
            $idGuru = null;
            $idStaf = null;
            $idSatpam = null;
            $nisnSiswa = null;

            // 2. Simpan data profil sesuai role
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

            // 3. Simpan akun ke tabel users
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

            // Redirect kembali ke halaman yang sama dengan pesan sukses
            return redirect()->route('admin.users.index')->with('success', 'User baru berhasil ditambahkan!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Gagal menyimpan user: ' . $e->getMessage()]);
        }
    }
}
<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Guru;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class GuruController
{
    /**
     * Menampilkan Halaman Data Guru & Pegawai.
     */
    public function index(Request $request)
    {
        $query = Guru::with('user');

        // Filter Pencarian Nama / NIP
        if ($request->filled('search')) {
            $keyword = trim($request->search);
            $query->where(function ($q) use ($keyword) {
                $q->where('nama_guru', 'LIKE', "%{$keyword}%")
                  ->orWhere('nip', 'LIKE', "%{$keyword}%")
                  ->orWhere('jabatan', 'LIKE', "%{$keyword}%");
            });
        }

        // Filter Jabatan / Role
        if ($request->filled('jabatan')) {
            $query->where('jabatan', $request->jabatan);
        }

        $perPage = (int) $request->input('per_page', 30);
        if ($perPage <= 0) $perPage = 30;

        $guruList = $query->orderBy('nama_guru')->paginate($perPage)->withQueryString();
        $totalGuru = Guru::count();

        return view('admin.guru.index', compact('guruList', 'totalGuru'));
    }

    /**
     * Menyimpan Pegawai / Guru Baru + Otomatis Buat Akun Login di tabel users.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nip'        => 'required|string|max:18|unique:guru,nip|unique:users,username',
            'nama_guru'  => 'required|string|max:150',
            'no_hp'      => 'nullable|string|max:15',
            'role'       => 'required|in:guru,guru_piket,staf_tu,satpam,kepala_sekolah,wakasis_siswa,wakasis_guru',
            'password'   => 'required|string|min:4',
        ], [
            'nip.required'       => 'NIP / Kode Pegawai wajib diisi.',
            'nip.unique'         => 'NIP ini sudah terdaftar.',
            'nama_guru.required' => 'Nama lengkap pegawai wajib diisi.',
            'password.required'  => 'Password wajib diisi.',
        ]);

        DB::beginTransaction();
        try {
            // 1. Simpan ke tabel guru
            $guru = Guru::create([
                'nip'        => $validated['nip'],
                'nama_guru'  => $validated['nama_guru'],
                'no_hp'      => $validated['no_hp'],
                'kode_mapel' => null, // Mapel tidak diisi di sini (mengikuti penugasan jadwal KBM)
                'jabatan'    => match ($validated['role']) {
                    'kepala_sekolah' => 'Kepala Sekolah',
                    'wakasis_siswa'  => 'Wakasis Siswa',
                    'wakasis_guru'   => 'Wakasis Guru',
                    'guru_piket'     => 'Guru Piket',
                    default          => 'Guru',
                },
            ]);

            // 2. Otomatis buat akun di tabel users
            User::create([
                'username'  => $validated['nip'],
                'password'  => Hash::make($validated['password']),
                'role'      => $validated['role'],
                'id_guru'   => $guru->id_guru,
                'is_active' => 1,
            ]);

            DB::commit();
            return redirect()->route('admin.guru.index')->with('success', 'Pegawai baru berhasil ditambahkan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Gagal menyimpan: ' . $e->getMessage()]);
        }
    }

    /**
     * Memperbarui Data Guru & Akun Loginnya.
     */
    public function update(Request $request, $id)
    {
        $guru = Guru::findOrFail($id);

        $validated = $request->validate([
            'nip'       => 'required|string|max:18|unique:guru,nip,' . $guru->id_guru . ',id_guru',
            'nama_guru' => 'required|string|max:150',
            'no_hp'     => 'nullable|string|max:15',
            'role'      => 'nullable|in:guru,guru_piket,staf_tu,satpam,kepala_sekolah,wakasis_siswa,wakasis_guru',
            'password'  => 'nullable|string|min:4',
        ], [
            'nip.required'       => 'NIP / Kode Pegawai wajib diisi.',
            'nip.unique'         => 'NIP ini sudah terdaftar.',
            'nama_guru.required' => 'Nama lengkap pegawai wajib diisi.',
        ]);

        DB::beginTransaction();
        try {
            // Update tabel guru
            $guru->update([
                'nip'       => $validated['nip'],
                'nama_guru' => $validated['nama_guru'],
                'no_hp'     => $validated['no_hp'],
                'jabatan'   => isset($validated['role']) ? match ($validated['role']) {
                    'kepala_sekolah' => 'Kepala Sekolah',
                    'wakasis_siswa'  => 'Wakasis Siswa',
                    'wakasis_guru'   => 'Wakasis Guru',
                    'guru_piket'     => 'Guru Piket',
                    default          => 'Guru',
                } : $guru->jabatan,
            ]);

            // Update tabel users
            $user = User::where('id_guru', $guru->id_guru)->first();
            if ($user) {
                $userUpdates = ['username' => $validated['nip']];
                if (!empty($validated['role'])) {
                    $userUpdates['role'] = $validated['role'];
                }
                if (!empty($validated['password'])) {
                    $userUpdates['password'] = Hash::make($validated['password']);
                }
                $user->update($userUpdates);
            }

            DB::commit();
            return redirect()->route('admin.guru.index')->with('success', 'Data pegawai berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Gagal memperbarui: ' . $e->getMessage()]);
        }
    }

    /**
     * Soft Delete Data Guru.
     */
    public function destroy($id)
    {
        $guru = Guru::findOrFail($id);
        User::where('id_guru', $guru->id_guru)->delete();
        $guru->delete();

        return redirect()->route('admin.guru.index')->with('success', 'Data pegawai berhasil dihapus (Soft Delete).');
    }

    /**
     * Menampilkan Data Guru di Tong Sampah (Trash / Soft Deleted).
     */
    public function trash()
    {
        $trashList = Guru::onlyTrashed()->paginate(30);
        return view('admin.guru.trash', compact('trashList'));
    }

    /**
     * Mengembalikan Data Guru dari Tong Sampah (Restore).
     */
    public function restore($id)
    {
        $guru = Guru::onlyTrashed()->findOrFail($id);
        $guru->restore();
        User::withTrashed()->where('id_guru', $guru->id_guru)->restore();

        return redirect()->route('admin.guru.trash')->with('success', 'Data guru berhasil dipulihkan!');
    }
}
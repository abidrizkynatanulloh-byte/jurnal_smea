<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Guru;
use App\Models\Mapel;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class GuruController
{
    /**
     * Menampilkan Halaman Data Guru & Pegawai (Form Tambah di Atas + Tabel di Bawah).
     */
    public function index(Request $request)
    {
        // 1. Data Mapel untuk Dropdown Form & Filter
        $mapelList = Mapel::orderBy('nama_mapel')->get();

        // 2. Query Data Guru dengan Filter Search & Mapel
        $query = Guru::query();

        // Filter Pencarian Nama / NIP
        if ($request->filled('search')) {
            $keyword = trim($request->search);
            $query->where(function ($q) use ($keyword) {
                $q->where('nama_guru', 'LIKE', "%{$keyword}%")
                  ->orWhere('nip', 'LIKE', "%{$keyword}%");
            });
        }

        // Filter Pilihan Mapel
        if ($request->filled('kode_mapel')) {
            $query->where('kode_mapel', $request->kode_mapel);
        }

        $guruList = $query->orderBy('nama_guru')->paginate(10)->withQueryString();
        $totalGuru = Guru::count();

        return view('admin.guru.index', compact('guruList', 'mapelList', 'totalGuru'));
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
            'kode_mapel' => 'nullable|exists:mapel,kode_mapel',
            'role'       => 'required|in:guru,guru_piket,staf_tu,satpam,kepala_sekolah,wakasis_siswa,wakasis_guru',
            'password'   => 'required|string|min:4',
        ], [
            'nip.required'       => 'NIP wajib diisi.',
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
                'kode_mapel' => $validated['kode_mapel'],
                'jabatan'    => match ($validated['role']) {
                    'kepala_sekolah' => 'Kepala Sekolah',
                    'wakasis_siswa'  => 'Wakasis Siswa',
                    'wakasis_guru'   => 'Wakasis Guru',
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
     * Memperbarui Data Guru.
     */
    public function update(Request $request, $id)
    {
        $guru = Guru::findOrFail($id);

        $validated = $request->validate([
            'nip'        => 'required|string|max:18|unique:guru,nip,' . $guru->id_guru . ',id_guru',
            'nama_guru'  => 'required|string|max:150',
            'no_hp'      => 'nullable|string|max:15',
            'kode_mapel' => 'nullable|exists:mapel,kode_mapel',
            'jabatan'    => 'nullable|in:Guru,Kepala Sekolah,Wakasis Siswa,Wakasis Guru',
        ], [
            'nip.required'       => 'NIP wajib diisi.',
            'nip.unique'         => 'NIP ini sudah terdaftar.',
            'nama_guru.required' => 'Nama lengkap pegawai wajib diisi.',
        ]);

        DB::beginTransaction();
        try {
            // Update username in users table if NIP changed
            if ($guru->nip !== $validated['nip']) {
                $user = User::where('id_guru', $guru->id_guru)->first();
                if ($user) {
                    $user->update(['username' => $validated['nip']]);
                }
            }

            $guru->update([
                'nip'        => $validated['nip'],
                'nama_guru'  => $validated['nama_guru'],
                'no_hp'      => $validated['no_hp'],
                'kode_mapel' => $validated['kode_mapel'],
                'jabatan'    => $validated['jabatan'] ?? $guru->jabatan,
            ]);

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
        $trashList = Guru::onlyTrashed()->paginate(10);
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
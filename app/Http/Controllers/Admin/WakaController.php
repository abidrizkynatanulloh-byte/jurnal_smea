<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Guru;

class WakaController
{
    public function index()
    {
        $wakaSiswa     = User::where('role', 'wakasis_siswa')->whereNotNull('id_guru')->with('guru')->get();
        $wakaKurikulum = User::whereIn('role', ['waka_kurikulum', 'wakasis_guru'])->whereNotNull('id_guru')->with('guru')->get();
        $wakaSdm       = User::where('role', 'waka_sdm')->whereNotNull('id_guru')->with('guru')->get();
        
        $semuaGuru = Guru::orderBy('nama_guru')->get();

        return view('admin.waka.index', compact('wakaSiswa', 'wakaKurikulum', 'wakaSdm', 'semuaGuru'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_guru' => 'required|exists:guru,id_guru',
            'tipe_waka' => 'required|in:wakasis_siswa,waka_kurikulum,wakasis_guru,waka_sdm',
        ]);

        $user = User::where('id_guru', $request->id_guru)->first();
        if (!$user) {
            return back()->withErrors(['error' => 'Guru tidak memiliki akun User.']);
        }

        $user->update(['role' => $request->tipe_waka]);

        $guru = Guru::find($request->id_guru);
        $jabatanBaru = match ($request->tipe_waka) {
            'wakasis_siswa'  => 'Waka Kesiswaan',
            'waka_kurikulum', 'wakasis_guru' => 'Waka Kurikulum',
            'waka_sdm'       => 'Waka SDM',
            default          => 'Waka',
        };
        if ($guru) {
            $guru->update(['jabatan' => $jabatanBaru]);
        }

        return redirect()->route('admin.waka.index')->with('success', "Jabatan {$jabatanBaru} berhasil diterapkan!");
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        
        // Kembalikan ke role guru
        $user->update(['role' => 'guru']);

        // Kembalikan jabatan
        if ($user->id_guru) {
            Guru::where('id_guru', $user->id_guru)->update(['jabatan' => 'Guru']);
        }

        return redirect()->route('admin.waka.index')->with('success', 'Waka berhasil diberhentikan (kembali jadi guru).');
    }
}

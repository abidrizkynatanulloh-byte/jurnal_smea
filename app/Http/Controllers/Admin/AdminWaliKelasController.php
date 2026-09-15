<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Kelas;
use App\Models\Guru;

class AdminWaliKelasController
{
    /**
     * Menampilkan Halaman Kelola Plotting Wali Kelas.
     */
    public function index(Request $request)
    {
        $query = Kelas::query();

        if ($request->filled('search')) {
            $keyword = trim($request->search);
            $query->where(function ($q) use ($keyword) {
                $q->where('nama_kelas', 'LIKE', "%{$keyword}%")
                  ->orWhere('wali_kelas', 'LIKE', "%{$keyword}%")
                  ->orWhereHas('waliKelasGuru', function ($g) use ($keyword) {
                      $g->where('nama_guru', 'LIKE', "%{$keyword}%")
                        ->orWhere('nip', 'LIKE', "%{$keyword}%");
                  });
            });
        }

        $perPage = (int) $request->input('per_page', 30);
        if ($perPage <= 0) $perPage = 30;

        $kelasList = $query->orderBy('nama_kelas')->paginate($perPage)->withQueryString();
        $guruList  = Guru::orderBy('nama_guru')->get();

        $totalKelas = Kelas::count();
        $totalPlotting = Kelas::whereNotNull('wali_kelas')->where('wali_kelas', '!=', '')->count();

        if ($request->ajax()) {
            return response()->json([
                'html'       => view('admin.wali-kelas.partials.rows', compact('kelasList', 'guruList'))->render(),
                'pagination' => view('components.pagination-bar', ['paginator' => $kelasList])->render(),
                'count'      => $kelasList->total(),
            ]);
        }

        return view('admin.wali-kelas.index', compact('kelasList', 'guruList', 'totalKelas', 'totalPlotting'));
    }

    /**
     * Simpan / Perbarui Wali Kelas untuk Suatu Kelas.
     */
    public function update(Request $request, $id)
    {
        $kelas = Kelas::findOrFail($id);

        $request->validate([
            'id_guru' => 'nullable|exists:guru,id_guru',
        ]);

        if ($request->filled('id_guru')) {
            $guru = Guru::findOrFail($request->id_guru);
            $kelas->update([
                'wali_kelas' => $guru->nip ?: $guru->id_guru,
            ]);
            $msg = "Wali Kelas untuk {$kelas->nama_kelas} berhasil diperbarui menjadi {$guru->nama_guru}.";
        } else {
            $kelas->update([
                'wali_kelas' => null,
            ]);
            $msg = "Wali Kelas untuk {$kelas->nama_kelas} berhasil dikosongkan.";
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $msg,
            ]);
        }

        return redirect()->route('admin.wali-kelas.index')->with('success', $msg);
    }

    /**
     * Hapus / Kosongkan Wali Kelas.
     */
    public function destroy($id)
    {
        $kelas = Kelas::findOrFail($id);
        $kelas->update(['wali_kelas' => null]);

        return redirect()->route('admin.wali-kelas.index')
            ->with('success', "Wali kelas untuk {$kelas->nama_kelas} berhasil dihapus.");
    }
}

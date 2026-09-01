<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\IzinGuru;
use App\Models\AuditLog;
use Carbon\Carbon;

class IzinGuruController extends Controller
{
    /**
     * Riwayat pengajuan izin guru login.
     */
    public function index()
    {
        $user = Auth::user();
        $idGuru = $user->id_guru ?? 1;

        $daftarIzin = IzinGuru::where('id_guru', $idGuru)
            ->orderBy('id', 'desc')
            ->get();

        return view('guru.izin.index', compact('daftarIzin'));
    }

    /**
     * Form pengajuan izin baru.
     */
    public function create()
    {
        return view('guru.izin.create');
    }

    /**
     * Simpan pengajuan izin ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'tanggal_mulai'   => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'alasan'          => 'required|string',
            'keterangan'      => 'nullable|string',
            'kelas_terdampak' => 'nullable|string',
            'bukti_foto'      => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'bukti_foto.required' => 'Unggah bukti pendukung (surat dokter / foto bukti) wajib dilampirkan.',
            'bukti_foto.image'    => 'File bukti pendukung harus berupa gambar (JPG, JPEG, PNG).',
        ]);

        $buktiPath = null;
        if ($request->hasFile('bukti_foto')) {
            $buktiPath = $request->file('bukti_foto')->store('izin_guru', 'public');
        }

        $user = Auth::user();
        $idGuru = $user->id_guru ?? 1;

        $izin = IzinGuru::create([
            'id_guru'         => $idGuru,
            'tanggal_mulai'   => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'alasan'          => $request->alasan,
            'keterangan'      => $request->keterangan,
            'kelas_terdampak' => $request->kelas_terdampak,
            'bukti_foto'      => $buktiPath,
            'status_waka'     => 'Menunggu',
            'status_sdm'      => 'Disetujui',
            'status_piket'    => 'Menunggu',
            'status_kepsek'   => 'Menunggu',
            'status_akhir'    => 'Diajukan',
        ]);

        AuditLog::log(
            'Pengajuan Izin Guru',
            "Guru mengajukan izin: {$izin->alasan} dari {$izin->tanggal_mulai} s/d {$izin->tanggal_selesai}"
        );

        return redirect()->route('guru.izin.index')
            ->with('success', 'Pengajuan izin berhasil dikirim ke Waka Kurikulum untuk tahap persetujuan pertama.');
    }
}

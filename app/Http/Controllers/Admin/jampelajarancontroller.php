<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\JamPelajaran;
use Carbon\Carbon;

class JamPelajaranController
{
    public function index()
    {
        $jamReguler = JamPelajaran::where('kelompok_hari', 'Reguler')->orderBy('jam_ke', 'asc')->get();
        $jamJumat = JamPelajaran::where('kelompok_hari', 'Jumat')->orderBy('jam_ke', 'asc')->get();

        return view('admin.jam_pelajaran.index', compact('jamReguler', 'jamJumat'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'jam_ke'        => 'required|integer|min:1',
            'kelompok_hari' => 'required|in:Reguler,Jumat',
            'waktu_mulai'   => 'required',
            'waktu_selesai' => 'required|after:waktu_mulai',
        ], [
            'waktu_selesai.after' => 'Waktu selesai harus lebih dari waktu mulai.',
        ]);

        // Cek unik per kelompok hari
        $sudahAda = JamPelajaran::where('jam_ke', $request->jam_ke)
            ->where('kelompok_hari', $request->kelompok_hari)
            ->exists();

        if ($sudahAda) {
            return back()->withErrors([
                'jam_ke' => "Jam ke-{$request->jam_ke} untuk kelompok {$request->kelompok_hari} sudah ada.",
            ])->withInput();
        }

        JamPelajaran::create($request->only(['jam_ke', 'kelompok_hari', 'waktu_mulai', 'waktu_selesai']));

        return redirect()->route('admin.jam.index')
            ->with('success', "Jam ke-{$request->jam_ke} ({$request->kelompok_hari}) berhasil ditambahkan!");
    }

    /**
     * Update waktu sesi jam.
     * Jika jam aktif terkecil diubah → semua jam lain ikut bergeser otomatis.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'waktu_mulai'   => 'required',
            'waktu_selesai' => 'required',
        ]);

        $jam = JamPelajaran::findOrFail($id);

        $jamAktifTerkecil = JamPelajaran::where('is_aktif', 1)
            ->orderBy('jam_ke', 'asc')->first();

        if ($jamAktifTerkecil && $jam->id_jam == $jamAktifTerkecil->id_jam) {
            $selisihMenit = Carbon::parse($jam->waktu_mulai)
                ->diffInMinutes(Carbon::parse($validated['waktu_mulai']), false);

            if ($selisihMenit !== 0) {
                JamPelajaran::where('id_jam', '!=', $jam->id_jam)->get()
                    ->each(function ($j) use ($selisihMenit) {
                        $j->update([
                            'waktu_mulai'   => Carbon::parse($j->waktu_mulai)->addMinutes($selisihMenit)->format('H:i:s'),
                            'waktu_selesai' => Carbon::parse($j->waktu_selesai)->addMinutes($selisihMenit)->format('H:i:s'),
                        ]);
                    });

                $arah  = $selisihMenit > 0 ? 'dimajukan' : 'dimundurkan';
                $menit = abs($selisihMenit);
                $pesan = "Jam ke-{$jam->jam_ke} diperbarui! Semua jam lain otomatis {$arah} {$menit} menit.";
            } else {
                $pesan = "Waktu Jam ke-{$jam->jam_ke} diperbarui!";
            }
        } else {
            $pesan = "Waktu Jam ke-{$jam->jam_ke} berhasil diperbarui!";
        }

        $jam->update($validated);

        return redirect()->route('admin.jam.index')->with('success', $pesan);
    }

    /**
     * Nonaktifkan jam → semua jam SETELAHNYA maju (lebih awal) sebesar durasi jam ini.
     */
    public function nonaktifkan($id)
    {
        $jam = JamPelajaran::findOrFail($id);

        if (!$jam->is_aktif) {
            return redirect()->route('admin.jam.index')
                ->with('info', "Jam ke-{$jam->jam_ke} sudah nonaktif.");
        }

        $durasiMenit = Carbon::parse($jam->waktu_mulai)
            ->diffInMinutes(Carbon::parse($jam->waktu_selesai));

        JamPelajaran::where('jam_ke', '>', $jam->jam_ke)->get()
            ->each(function ($j) use ($durasiMenit) {
                $j->update([
                    'waktu_mulai'   => Carbon::parse($j->waktu_mulai)->subMinutes($durasiMenit)->format('H:i:s'),
                    'waktu_selesai' => Carbon::parse($j->waktu_selesai)->subMinutes($durasiMenit)->format('H:i:s'),
                ]);
            });

        $jam->update(['is_aktif' => 0]);

        return redirect()->route('admin.jam.index')
            ->with('success', "Jam ke-{$jam->jam_ke} dinonaktifkan! Jam setelahnya otomatis maju {$durasiMenit} menit.");
    }

    /**
     * Aktifkan kembali jam → semua jam SETELAHNYA mundur sebesar durasi jam ini.
     */
    public function aktifkan($id)
    {
        $jam = JamPelajaran::findOrFail($id);

        if ($jam->is_aktif) {
            return redirect()->route('admin.jam.index')
                ->with('info', "Jam ke-{$jam->jam_ke} sudah aktif.");
        }

        $durasiMenit = Carbon::parse($jam->waktu_mulai)
            ->diffInMinutes(Carbon::parse($jam->waktu_selesai));

        JamPelajaran::where('jam_ke', '>', $jam->jam_ke)->get()
            ->each(function ($j) use ($durasiMenit) {
                $j->update([
                    'waktu_mulai'   => Carbon::parse($j->waktu_mulai)->addMinutes($durasiMenit)->format('H:i:s'),
                    'waktu_selesai' => Carbon::parse($j->waktu_selesai)->addMinutes($durasiMenit)->format('H:i:s'),
                ]);
            });

        $jam->update(['is_aktif' => 1]);

        return redirect()->route('admin.jam.index')
            ->with('success', "Jam ke-{$jam->jam_ke} diaktifkan kembali! Jam setelahnya otomatis mundur {$durasiMenit} menit.");
    }

    public function destroy($id)
    {
        $jam = JamPelajaran::findOrFail($id);
        $nomorJam = $jam->jam_ke;
        $jam->delete();

        return redirect()->route('admin.jam.index')
            ->with('success', "Sesi jam ke-{$nomorJam} berhasil dihapus permanen.");
    }
}
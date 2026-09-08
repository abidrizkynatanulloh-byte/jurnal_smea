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

        // Cek unik per kelompok hari (termasuk data soft deleted)
        $existing = JamPelajaran::withTrashed()
            ->where('jam_ke', $request->jam_ke)
            ->where('kelompok_hari', $request->kelompok_hari)
            ->first();

        if ($existing) {
            if ($existing->trashed()) {
                $existing->restore();
                $existing->update([
                    'waktu_mulai'   => $request->waktu_mulai,
                    'waktu_selesai' => $request->waktu_selesai,
                    'is_aktif'      => 1,
                ]);

                return redirect()->route('admin.jam.index')
                    ->with('success', "Jam ke-{$request->jam_ke} ({$request->kelompok_hari}) berhasil ditambahkan!");
            }

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
     * Jika waktu selesai atau waktu mulai diubah → semua jam setelahnya pada kelompok hari yang sama ikut bergeser otomatis.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'jam_ke'        => 'required|integer|min:1',
            'kelompok_hari' => 'required|in:Reguler,Jumat',
            'waktu_mulai'   => 'required',
            'waktu_selesai' => 'required|after:waktu_mulai',
        ], [
            'waktu_selesai.after' => 'Waktu selesai harus lebih dari waktu mulai.',
        ]);

        $jam = JamPelajaran::findOrFail($id);

        // Cek jika jam_ke atau kelompok_hari diubah, pastikan tidak bentrok dengan data lain
        $duplikat = JamPelajaran::withTrashed()
            ->where('id_jam', '!=', $id)
            ->where('jam_ke', $request->jam_ke)
            ->where('kelompok_hari', $request->kelompok_hari)
            ->first();

        if ($duplikat) {
            if ($duplikat->trashed()) {
                $duplikat->forceDelete();
            } else {
                return back()->withErrors([
                    'jam_ke' => "Jam ke-{$request->jam_ke} untuk kelompok {$request->kelompok_hari} sudah digunakan oleh sesi lain.",
                ])->withInput();
            }
        }

        $isAktifBaru = $request->has('is_aktif') ? 1 : 0;

        // Hitung selisih perubahan waktu selesai dan waktu mulai
        $selisihSelesai = Carbon::parse($jam->waktu_selesai)
            ->diffInMinutes(Carbon::parse($validated['waktu_selesai']), false);

        $selisihMulai = Carbon::parse($jam->waktu_mulai)
            ->diffInMinutes(Carbon::parse($validated['waktu_mulai']), false);

        $pesan = "Jam ke-{$request->jam_ke} ({$request->kelompok_hari}) berhasil diperbarui!";

        // Jika waktu_selesai diubah (misal durasi ditambah 1 menit), geser semua jam setelahnya
        if ($selisihSelesai !== 0) {
            JamPelajaran::where('kelompok_hari', $request->kelompok_hari)
                ->where('jam_ke', '>', $jam->jam_ke)
                ->get()
                ->each(function ($j) use ($selisihSelesai) {
                    $j->update([
                        'waktu_mulai'   => Carbon::parse($j->waktu_mulai)->addMinutes($selisihSelesai)->format('H:i:s'),
                        'waktu_selesai' => Carbon::parse($j->waktu_selesai)->addMinutes($selisihSelesai)->format('H:i:s'),
                    ]);
                });

            $arah  = $selisihSelesai > 0 ? 'dimundurkan' : 'dimajukan';
            $menit = abs($selisihSelesai);
            $pesan = "Jam ke-{$request->jam_ke} diperbarui! Semua jam setelahnya ({$request->kelompok_hari}) otomatis {$arah} {$menit} menit.";
        } elseif ($selisihMulai !== 0) {
            // Jika hanya waktu_mulai jam pertama yang diubah
            $jamPertama = JamPelajaran::where('kelompok_hari', $request->kelompok_hari)
                ->orderBy('jam_ke', 'asc')
                ->first();

            if ($jamPertama && $jam->id_jam == $jamPertama->id_jam) {
                JamPelajaran::where('kelompok_hari', $request->kelompok_hari)
                    ->where('id_jam', '!=', $jam->id_jam)
                    ->get()
                    ->each(function ($j) use ($selisihMulai) {
                        $j->update([
                            'waktu_mulai'   => Carbon::parse($j->waktu_mulai)->addMinutes($selisihMulai)->format('H:i:s'),
                            'waktu_selesai' => Carbon::parse($j->waktu_selesai)->addMinutes($selisihMulai)->format('H:i:s'),
                        ]);
                    });

                $arah  = $selisihMulai > 0 ? 'dimundurkan' : 'dimajukan';
                $menit = abs($selisihMulai);
                $pesan = "Jam ke-{$request->jam_ke} diperbarui! Semua jam setelahnya ({$request->kelompok_hari}) otomatis {$arah} {$menit} menit.";
            }
        }

        $jam->update([
            'jam_ke'        => $request->jam_ke,
            'kelompok_hari' => $request->kelompok_hari,
            'waktu_mulai'   => $request->waktu_mulai,
            'waktu_selesai' => $request->waktu_selesai,
            'is_aktif'      => $isAktifBaru,
        ]);

        return redirect()->route('admin.jam.index')->with('success', $pesan);
    }

    /**
     * Nonaktifkan jam → semua jam SETELAHNYA pada kelompok hari yang sama maju (lebih awal) sebesar durasi jam ini.
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

        JamPelajaran::where('kelompok_hari', $jam->kelompok_hari)
            ->where('jam_ke', '>', $jam->jam_ke)
            ->get()
            ->each(function ($j) use ($durasiMenit) {
                $j->update([
                    'waktu_mulai'   => Carbon::parse($j->waktu_mulai)->subMinutes($durasiMenit)->format('H:i:s'),
                    'waktu_selesai' => Carbon::parse($j->waktu_selesai)->subMinutes($durasiMenit)->format('H:i:s'),
                ]);
            });

        $jam->update(['is_aktif' => 0]);

        return redirect()->route('admin.jam.index')
            ->with('success', "Jam ke-{$jam->jam_ke} ({$jam->kelompok_hari}) dinonaktifkan! Jam setelahnya otomatis maju {$durasiMenit} menit.");
    }

    /**
     * Aktifkan kembali jam → semua jam SETELAHNYA pada kelompok hari yang sama mundur sebesar durasi jam ini.
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

        JamPelajaran::where('kelompok_hari', $jam->kelompok_hari)
            ->where('jam_ke', '>', $jam->jam_ke)
            ->get()
            ->each(function ($j) use ($durasiMenit) {
                $j->update([
                    'waktu_mulai'   => Carbon::parse($j->waktu_mulai)->addMinutes($durasiMenit)->format('H:i:s'),
                    'waktu_selesai' => Carbon::parse($j->waktu_selesai)->addMinutes($durasiMenit)->format('H:i:s'),
                ]);
            });

        $jam->update(['is_aktif' => 1]);

        return redirect()->route('admin.jam.index')
            ->with('success', "Jam ke-{$jam->jam_ke} ({$jam->kelompok_hari}) diaktifkan kembali! Jam setelahnya otomatis mundur {$durasiMenit} menit.");
    }

    public function destroy($id)
    {
        $jam = JamPelajaran::withTrashed()->findOrFail($id);
        $nomorJam = $jam->jam_ke;
        $jam->forceDelete();

        return redirect()->route('admin.jam.index')
            ->with('success', "Sesi jam ke-{$nomorJam} berhasil dihapus permanen.");
    }
}
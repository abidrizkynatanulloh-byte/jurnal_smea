<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\JamPelajaran;
use Carbon\Carbon;

class JamPelajaranController
{
    /**
     * Tampilkan halaman master jam pelajaran.
     * Otomatis merapikan jika ada data jadwal yang saling tumpang tindih atau bergeser.
     */
    public function index()
    {
        $this->recalculateSchedule('Reguler');
        $this->recalculateSchedule('Jumat');

        $jamReguler = JamPelajaran::where('kelompok_hari', 'Reguler')->orderBy('jam_ke', 'asc')->get();
        $jamJumat   = JamPelajaran::where('kelompok_hari', 'Jumat')->orderBy('jam_ke', 'asc')->get();

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
            } else {
                return back()->withErrors([
                    'jam_ke' => "Jam ke-{$request->jam_ke} untuk kelompok {$request->kelompok_hari} sudah ada.",
                ])->withInput();
            }
        } else {
            JamPelajaran::create($request->only(['jam_ke', 'kelompok_hari', 'waktu_mulai', 'waktu_selesai']));
        }

        $this->recalculateSchedule($request->kelompok_hari);

        return redirect()->route('admin.jam.index')
            ->with('success', "Jam ke-{$request->jam_ke} ({$request->kelompok_hari}) berhasil ditambahkan!");
    }

    /**
     * Update waktu sesi jam.
     * Setelah disimpan, seluruh jam pelajaran aktif dalam kelompok hari tersebut otomatis dirapikan secara berurutan.
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

        // Hitung selisih net shift sebelum di-update
        $selisihSelesai = Carbon::parse($jam->waktu_selesai)
            ->diffInMinutes(Carbon::parse($validated['waktu_selesai']), false);
        $selisihMulai   = Carbon::parse($jam->waktu_mulai)
            ->diffInMinutes(Carbon::parse($validated['waktu_mulai']), false);

        $netShift = ($selisihSelesai !== 0) ? $selisihSelesai : $selisihMulai;

        $isAktifBaru = $request->has('is_aktif') ? 1 : 0;

        $jam->update([
            'jam_ke'        => $request->jam_ke,
            'kelompok_hari' => $request->kelompok_hari,
            'waktu_mulai'   => $request->waktu_mulai,
            'waktu_selesai' => $request->waktu_selesai,
            'is_aktif'      => $isAktifBaru,
        ]);

        // Rapikan ulang jadwal seluruh jam pelajaran secara berurutan
        $this->recalculateSchedule($request->kelompok_hari);

        if ($netShift !== 0) {
            $arah  = $netShift > 0 ? 'dimundurkan' : 'dimajukan';
            $menit = abs($netShift);
            $pesan = "Jam ke-{$request->jam_ke} ({$request->kelompok_hari}) diperbarui! Seluruh jadwal jam setelahnya otomatis {$arah} {$menit} menit.";
        } else {
            $pesan = "Jam ke-{$request->jam_ke} ({$request->kelompok_hari}) berhasil diperbarui!";
        }

        return redirect()->route('admin.jam.index')->with('success', $pesan);
    }

    /**
     * Nonaktifkan jam → jam aktif setelahnya otomatis merapat maju.
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

        $jam->update(['is_aktif' => 0]);

        $this->recalculateSchedule($jam->kelompok_hari);

        return redirect()->route('admin.jam.index')
            ->with('success', "Jam ke-{$jam->jam_ke} ({$jam->kelompok_hari}) dinonaktifkan! Jadwal jam setelahnya otomatis dimajukan {$durasiMenit} menit.");
    }

    /**
     * Aktifkan kembali jam → jadwal disesuaikan kembali berurutan.
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

        $jam->update(['is_aktif' => 1]);

        $this->recalculateSchedule($jam->kelompok_hari);

        return redirect()->route('admin.jam.index')
            ->with('success', "Jam ke-{$jam->jam_ke} ({$jam->kelompok_hari}) diaktifkan kembali! Jadwal jam setelahnya otomatis dimundurkan {$durasiMenit} menit.");
    }

    public function destroy($id)
    {
        $jam = JamPelajaran::withTrashed()->findOrFail($id);
        $nomorJam = $jam->jam_ke;
        $kelompokHari = $jam->kelompok_hari;
        $jam->forceDelete();

        $this->recalculateSchedule($kelompokHari);

        return redirect()->route('admin.jam.index')
            ->with('success', "Sesi jam ke-{$nomorJam} berhasil dihapus permanen.");
    }

    /**
     * Mengurutkan dan merapikan kembali jadwal seluruh jam pelajaran aktif secara berurutan.
     * Jika Jam ke-1 dinonaktifkan, jam aktif pertama otomatis dimajukan ke jam awal sekolah (07.00).
     */
    private function recalculateSchedule($kelompokHari)
    {
        // 1. Dapatkan jam pelajaran pertama sebagai patokan jam mulai sekolah (misal 07:00)
        $firstSession = JamPelajaran::withTrashed()
            ->where('kelompok_hari', $kelompokHari)
            ->orderBy('jam_ke', 'asc')
            ->first();

        $baseStartTime = $firstSession ? $firstSession->waktu_mulai : '07:00:00';
        if (empty($baseStartTime)) {
            $baseStartTime = '07:00:00';
        }

        // 2. Dapatkan seluruh jam pelajaran yang aktif
        $jamList = JamPelajaran::where('kelompok_hari', $kelompokHari)
            ->where('is_aktif', 1)
            ->orderBy('jam_ke', 'asc')
            ->get();

        if ($jamList->isEmpty()) {
            return;
        }

        // 3. Set jam pelajaran aktif pertama agar mulai tepat di jam awal sekolah (baseStartTime)
        $firstActive = $jamList[0];
        $durasiFirst = Carbon::parse($firstActive->waktu_mulai)
            ->diffInMinutes(Carbon::parse($firstActive->waktu_selesai));

        if ($durasiFirst <= 0) {
            $durasiFirst = ($kelompokHari === 'Jumat') ? 35 : 40;
        }

        $newFirstMulai   = Carbon::parse($baseStartTime);
        $newFirstSelesai = (clone $newFirstMulai)->addMinutes($durasiFirst);

        $firstActive->update([
            'waktu_mulai'   => $newFirstMulai->format('H:i:s'),
            'waktu_selesai' => $newFirstSelesai->format('H:i:s'),
        ]);

        $jamList[0]->waktu_mulai   = $newFirstMulai->format('H:i:s');
        $jamList[0]->waktu_selesai = $newFirstSelesai->format('H:i:s');

        // 4. Hitung dan sesuaikan jam pelajaran aktif berikutnya secara berurutan
        for ($i = 0; $i < $jamList->count() - 1; $i++) {
            $current = $jamList[$i];
            $next    = $jamList[$i + 1];

            $durasiNext = Carbon::parse($next->waktu_mulai)
                ->diffInMinutes(Carbon::parse($next->waktu_selesai));

            if ($durasiNext <= 0) {
                $durasiNext = ($kelompokHari === 'Jumat') ? 35 : 40;
            }

            $gapMenit = $this->getBreakGapMinutes($kelompokHari, $current->jam_ke, $next->jam_ke);

            $newNextMulai   = Carbon::parse($current->waktu_selesai)->addMinutes($gapMenit);
            $newNextSelesai = (clone $newNextMulai)->addMinutes($durasiNext);

            $next->update([
                'waktu_mulai'   => $newNextMulai->format('H:i:s'),
                'waktu_selesai' => $newNextSelesai->format('H:i:s'),
            ]);

            $jamList[$i + 1]->waktu_mulai   = $newNextMulai->format('H:i:s');
            $jamList[$i + 1]->waktu_selesai = $newNextSelesai->format('H:i:s');
        }
    }

    /**
     * Hitung durasi jeda istirahat (dalam menit) di antara dua jam pelajaran aktif.
     * Senin-Kamis: Istirahat 1 sela jam 4-5 (20 menit), Istirahat 2 sela jam 7-8 (90 menit).
     * Jumat: Istirahat 1 sela jam 4-5 (20 menit), Istirahat 2 sela jam 8-9 (100 menit).
     */
    private function getBreakGapMinutes($kelompokHari, $currentJamKe, $nextJamKe)
    {
        $gap = 0;

        // Istirahat 1 (di sela-sela jam 4 dan jam 5)
        if ($currentJamKe <= 4 && $nextJamKe >= 5) {
            $gap += 20; // 20 menit istirahat 1
        }

        if ($kelompokHari === 'Jumat') {
            // Istirahat 2 / Jumatan (di sela-sela jam 8 dan jam 9)
            if ($currentJamKe <= 8 && $nextJamKe >= 9) {
                $gap += 100; // 100 menit ISHOMA Jumat (11.20 - 13.00)
            }
        } else {
            // Istirahat 2 / ISHOMA Reguler (di sela-sela jam 7 dan jam 8)
            if ($currentJamKe <= 7 && $nextJamKe >= 8) {
                $gap += 90; // 90 menit ISHOMA Reguler (11.45 - 13.15)
            }
        }

        return $gap;
    }
}
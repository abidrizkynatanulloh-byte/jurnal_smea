<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\EventSekolah;
use Carbon\Carbon;

class EventSekolahController
{
    /**
     * Daftar semua event sekolah.
     */
    public function index(Request $request)
    {
        $bulan  = $request->input('bulan', date('n'));
        $tahun  = $request->input('tahun', date('Y'));
        $search = $request->input('search');

        $query = EventSekolah::orderBy('tanggal_mulai', 'desc');

        if ($search) {
            $query->where('nama_event', 'like', "%{$search}%");
        }

        if ($bulan && $tahun) {
            $query->where(function ($q) use ($bulan, $tahun) {
                $startBulan = Carbon::createFromDate($tahun, $bulan, 1)->startOfMonth()->toDateString();
                $endBulan   = Carbon::createFromDate($tahun, $bulan, 1)->endOfMonth()->toDateString();
                $q->whereBetween('tanggal_mulai', [$startBulan, $endBulan])
                  ->orWhereBetween('tanggal_selesai', [$startBulan, $endBulan])
                  ->orWhere(function ($q2) use ($startBulan, $endBulan) {
                      $q2->where('tanggal_mulai', '<=', $startBulan)
                         ->where('tanggal_selesai', '>=', $endBulan);
                  });
            });
        }

        $daftarEvent = $query->get();

        $namaBulanList = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];

        return view('Admin.event_sekolah.index', compact(
            'daftarEvent', 'bulan', 'tahun', 'search', 'namaBulanList'
        ));
    }

    /**
     * Simpan event baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_event'      => 'required|string|max:200',
            'jenis'           => 'required|in:event,pulang_pagi',
            'tanggal_mulai'   => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'jam_pulang'      => 'required_if:jenis,pulang_pagi|nullable|date_format:H:i',
            'keterangan'      => 'nullable|string|max:500',
        ], [
            'jam_pulang.required_if' => 'Jam pulang wajib diisi untuk tipe Pulang Pagi.',
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai harus sama atau setelah tanggal mulai.',
        ]);

        EventSekolah::create([
            'nama_event'      => $request->nama_event,
            'jenis'           => $request->jenis,
            'tanggal_mulai'   => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'jam_pulang'      => $request->jenis === 'pulang_pagi' ? $request->jam_pulang . ':00' : null,
            'keterangan'      => $request->keterangan,
        ]);

        return back()->with('success', 'Event sekolah berhasil ditambahkan!');
    }

    /**
     * Update event.
     */
    public function update(Request $request, $id)
    {
        $event = EventSekolah::findOrFail($id);

        $request->validate([
            'nama_event'      => 'required|string|max:200',
            'jenis'           => 'required|in:event,pulang_pagi',
            'tanggal_mulai'   => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'jam_pulang'      => 'required_if:jenis,pulang_pagi|nullable|date_format:H:i',
            'keterangan'      => 'nullable|string|max:500',
        ], [
            'jam_pulang.required_if' => 'Jam pulang wajib diisi untuk tipe Pulang Pagi.',
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai harus sama atau setelah tanggal mulai.',
        ]);

        $event->update([
            'nama_event'      => $request->nama_event,
            'jenis'           => $request->jenis,
            'tanggal_mulai'   => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'jam_pulang'      => $request->jenis === 'pulang_pagi' ? $request->jam_pulang . ':00' : null,
            'keterangan'      => $request->keterangan,
        ]);

        return back()->with('success', 'Event sekolah berhasil diperbarui!');
    }

    /**
     * Hapus event.
     */
    public function destroy($id)
    {
        $event = EventSekolah::findOrFail($id);
        $event->delete();

        return back()->with('success', "Event \"{$event->nama_event}\" berhasil dihapus.");
    }
}

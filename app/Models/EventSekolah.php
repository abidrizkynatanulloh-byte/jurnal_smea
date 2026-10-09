<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class EventSekolah extends Model
{
    protected $table = 'event_sekolah';

    protected $fillable = [
        'nama_event',
        'jenis',
        'tanggal_mulai',
        'tanggal_selesai',
        'jam_pulang',
        'keterangan',
    ];

    protected $casts = [
        'tanggal_mulai'  => 'date',
        'tanggal_selesai' => 'date',
    ];

    /**
     * Cek apakah tanggal tertentu masuk dalam rentang event ini.
     */
    public function meliputiTanggal(string $tanggal): bool
    {
        return $tanggal >= $this->tanggal_mulai->toDateString()
            && $tanggal <= $this->tanggal_selesai->toDateString();
    }

    /**
     * [STATIC] Cek apakah suatu tanggal + waktu mulai mengajar (H:i:s) termasuk EXEMPT (bebas jurnal).
     *
     * @param  string       $tanggal      Format Y-m-d
     * @param  string|null  $waktuMulai   Format H:i:s — jam mulai mengajar guru (untuk cek pulang pagi)
     * @return array{exempt: bool, alasan: string|null}
     */
    public static function cekExempt(string $tanggal, ?string $waktuMulai = null): array
    {
        // Ambil semua event yang meliput tanggal ini
        $events = self::where('tanggal_mulai', '<=', $tanggal)
            ->where('tanggal_selesai', '>=', $tanggal)
            ->get();

        foreach ($events as $event) {
            if ($event->jenis === 'event') {
                // Semua guru bebas seharian
                return [
                    'exempt' => true,
                    'alasan' => "Event: {$event->nama_event}",
                ];
            }

            if ($event->jenis === 'pulang_pagi' && $event->jam_pulang && $waktuMulai) {
                // Guru yang jam mulai mengajarnya >= jam_pulang => bebas
                if ($waktuMulai >= $event->jam_pulang) {
                    $jamPulangTeks = substr($event->jam_pulang, 0, 5);
                    return [
                        'exempt' => true,
                        'alasan' => "Pulang Pagi pk. {$jamPulangTeks} ({$event->nama_event})",
                    ];
                }
            }
        }

        return ['exempt' => false, 'alasan' => null];
    }

    /**
     * Label jenis event yang lebih ramah.
     */
    public function getJenisLabelAttribute(): string
    {
        return match ($this->jenis) {
            'event'       => 'Event / Libur',
            'pulang_pagi' => 'Pulang Pagi',
            default       => $this->jenis,
        };
    }

    /**
     * Durasi event dalam hari.
     */
    public function getDurasiHariAttribute(): int
    {
        return $this->tanggal_mulai->diffInDays($this->tanggal_selesai) + 1;
    }
}

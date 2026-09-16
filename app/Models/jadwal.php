<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Jadwal extends Model
{
    use SoftDeletes;

    protected $table = 'jadwal';
    protected $primaryKey = 'id_jadwal';
    public $timestamps = false;

    protected $fillable = [
        'id_kelas',
        'id_guru',
        'id_ruangan',
        'hari',
        'jam_mulai',
        'jam_selesai',
        'kode_mapel',
    ];

    // Relasi ke Kelas
    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'id_kelas', 'id_kelas');
    }

    // Relasi ke Guru
    public function guru()
    {
        return $this->belongsTo(Guru::class, 'id_guru', 'id_guru');
    }

    // Relasi ke Mapel
    public function mapel()
    {
        return $this->belongsTo(Mapel::class, 'kode_mapel', 'kode_mapel');
    }

    // Relasi ke Ruangan
    public function ruangan()
    {
        return $this->belongsTo(Ruangan::class, 'id_ruangan', 'id_ruangan');
    }
    // Relasi jam mulai ke jam pelajaran
    public function jamMulaiData()
    {
        return $this->belongsTo(JamPelajaran::class, 'jam_mulai', 'jam_ke');
    }

    // Relasi jam selesai ke jam pelajaran
    public function jamSelesaiData()
    {
        return $this->belongsTo(JamPelajaran::class, 'jam_selesai', 'jam_ke');
    }

    /**
     * Dapatkan detail data JamPelajaran (Mulai / Selesai) dengan fallback cerdas.
     */
    public function getJamPelajaranDetail($jamKe, $kelompok = 'Reguler')
    {
        $jp = JamPelajaran::where('jam_ke', $jamKe)
            ->where('kelompok_hari', $kelompok)
            ->first();

        if (!$jp) {
            $jp = JamPelajaran::where('jam_ke', $jamKe)->first();
        }

        return $jp;
    }

    /**
     * Waktu Mulai & Selesai Jam Pelajaran dalam format string H:i:s.
     */
    public function getWaktuMulaiSelesai()
    {
        $now      = \Carbon\Carbon::now('Asia/Jakarta');
        $hariIni  = $now->format('l');
        $kelompok = ($hariIni === 'Friday') ? 'Jumat' : 'Reguler';

        $jamMulaiObj   = $this->getJamPelajaranDetail($this->jam_mulai, $kelompok);
        $jamSelesaiObj = $this->getJamPelajaranDetail($this->jam_selesai, $kelompok);

        $waktuMulai   = $jamMulaiObj ? $jamMulaiObj->waktu_mulai : '07:00:00';
        $waktuSelesai = $jamSelesaiObj ? $jamSelesaiObj->waktu_selesai : '15:30:00';

        // Jika jam_selesai > 10 dan tidak ditemukan, hitung estimasi 35-40 menit per jam
        if (!$jamSelesaiObj && $this->jam_selesai > 10) {
            $lastKnown = JamPelajaran::where('kelompok_hari', $kelompok)->orderBy('jam_ke', 'desc')->first();
            if ($lastKnown) {
                $selisihJam = $this->jam_selesai - $lastKnown->jam_ke;
                $waktuSelesai = \Carbon\Carbon::parse($lastKnown->waktu_selesai)
                    ->addMinutes($selisihJam * 35)->format('H:i:s');
            }
        }

        return [
            'waktu_mulai'   => $waktuMulai,
            'waktu_selesai' => $waktuSelesai,
        ];
    }

    /**
     * Cek status waktu pengisian jurnal ('belum', 'sekarang', 'telat').
     * Otomatis membedakan jadwal Reguler (Senin-Kamis) vs Jumat.
     */
    public function statusWaktuMengajar()
    {
        $now       = \Carbon\Carbon::now('Asia/Jakarta');
        $sekarang  = $now->format('H:i:s');
        $times     = $this->getWaktuMulaiSelesai();

        $waktuMulai = $times['waktu_mulai'];
        // Toleransi +10 menit setelah jam mengajar berakhir
        $waktuSelesaiToleransi = \Carbon\Carbon::parse($times['waktu_selesai'])
            ->addMinutes(10)->format('H:i:s');

        if ($sekarang < $waktuMulai) {
            return 'belum';
        } elseif ($sekarang > $waktuSelesaiToleransi) {
            return 'telat';
        } else {
            return 'sekarang';
        }
    }

    /**
     * Hitung sisa menit sesi mengajar jika sedang berlangsung.
     * Mengembalikan jumlah menit tersisa (misal: 5, 3, 1) atau null jika tidak aktif.
     */
    public function getSisaMenitSesi()
    {
        $now    = \Carbon\Carbon::now('Asia/Jakarta');
        $times  = $this->getWaktuMulaiSelesai();
        $target = \Carbon\Carbon::parse($times['waktu_selesai']);

        if ($now->format('H:i:s') >= $times['waktu_mulai'] && $now <= $target->copy()->addMinutes(10)) {
            $diffInMinutes = (int) ceil($now->diffInSeconds($target, false) / 60);
            return $diffInMinutes;
        }

        return null;
    }
}
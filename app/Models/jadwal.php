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

    // Fungsi untuk mengecek status waktu pengisian jurnal
    // Otomatis membedakan jadwal Reguler (Senin-Kamis) vs Jumat
    public function statusWaktuMengajar()
    {
        $now       = \Carbon\Carbon::now('Asia/Jakarta');
        $hariIni   = $now->format('l'); // 'Monday', 'Friday', dst
        $kelompok  = ($hariIni === 'Friday') ? 'Jumat' : 'Reguler';

        // Ambil jam mulai & selesai sesuai kelompok hari
        $jamMulaiData = \App\Models\JamPelajaran::where('jam_ke', $this->jam_mulai)
            ->where('kelompok_hari', $kelompok)
            ->where('is_aktif', 1)
            ->first();

        $jamSelesaiData = \App\Models\JamPelajaran::where('jam_ke', $this->jam_selesai)
            ->where('kelompok_hari', $kelompok)
            ->where('is_aktif', 1)
            ->first();

        // Fallback ke Reguler jika kelompok Jumat belum diisi
        if (!$jamMulaiData && $kelompok === 'Jumat') {
            $jamMulaiData = \App\Models\JamPelajaran::where('jam_ke', $this->jam_mulai)
                ->where('kelompok_hari', 'Reguler')
                ->where('is_aktif', 1)->first();
        }
        if (!$jamSelesaiData && $kelompok === 'Jumat') {
            $jamSelesaiData = \App\Models\JamPelajaran::where('jam_ke', $this->jam_selesai)
                ->where('kelompok_hari', 'Reguler')
                ->where('is_aktif', 1)->first();
        }

        if (!$jamMulaiData || !$jamSelesaiData) return 'error';

        $sekarang   = $now->format('H:i:s');
        $waktuMulai = $jamMulaiData->waktu_mulai;

        // Toleransi +10 menit setelah jam mengajar berakhir
        $waktuSelesaiToleransi = \Carbon\Carbon::parse($jamSelesaiData->waktu_selesai)
            ->addMinutes(10)->format('H:i:s');

        if ($sekarang < $waktuMulai) {
            return 'belum';
        } elseif ($sekarang > $waktuSelesaiToleransi) {
            return 'telat';
        } else {
            return 'sekarang';
        }
    }
}
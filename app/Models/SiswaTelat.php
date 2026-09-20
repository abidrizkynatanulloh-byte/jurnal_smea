<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Model SiswaTelat
 * Digunakan untuk mengelola pendataan keterlambatan siswa yang dicatat oleh Guru Piket.
 */
class SiswaTelat extends Model
{
    // Fitur hapus sementara (Soft Delete)
    use SoftDeletes;

    // Nama tabel di database
    protected $table = 'siswa_telat';

    // Daftar kolom yang diizinkan diisi
    protected $fillable = [
        'nis',             // NIS siswa yang terlambat
        'tanggal',         // Tanggal siswa datang terlambat
        'jam_terlambat',   // Jam/waktu siswa tiba di sekolah
        'alasan',          // Alasan/penyebab keterlambatan
        'tindakan',        // Sanksi/pembinaan yang diberikan oleh piket
        'id_guru_piket',   // ID guru piket yang mencatat
    ];

    /**
     * Relasi ke data Siswa (Setiap catatan terlambat milik satu siswa)
     */
    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'nis', 'nis');
    }

    /**
     * Relasi ke data Guru Piket yang mencatat keterlambatan ini
     */
    public function guruPiket()
    {
        return $this->belongsTo(Guru::class, 'id_guru_piket', 'id_guru');
    }
}

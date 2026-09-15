<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Model IzinSiswa
 * Digunakan untuk mengelola permohonan izin / sakit siswa yang diajukan oleh Orang Tua.
 */
class IzinSiswa extends Model
{
    // Mengaktifkan fitur hapus sementara (Soft Delete)
    use SoftDeletes;

    // Nama tabel di database
    protected $table = 'izin_siswa';

    // Daftar kolom yang diizinkan untuk diisi secara langsung
    protected $fillable = [
        'nis',                // NIS siswa yang diajukan izinnya
        'kategori',           // Kategori permohonan (Sakit / Izin)
        'alasan',             // Penjelasan/alasan ketidakhadiran
        'tanggal_mulai',      // Tanggal mulai izin
        'tanggal_selesai',    // Tanggal berakhirnya izin
        'bukti_foto',         // Path file surat dokter / surat ortu yang diunggah
        'status',             // Status permohonan: Pending, Disetujui, Ditolak
        'disetujui_oleh',    // ID User (Guru Piket) yang memproses izin ini
        'catatan_penolakan',  // Catatan alasan penolakan jika izin ditolak
    ];

    /**
     * Relasi ke data Siswa (Setiap permohonan izin milik satu siswa)
     */
    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'nis', 'nisn');
    }

    /**
     * Relasi ke User yang menyetujui izin ini (Guru Piket)
     */
    public function disetujuiOleh()
    {
        return $this->belongsTo(User::class, 'disetujui_oleh');
    }
}

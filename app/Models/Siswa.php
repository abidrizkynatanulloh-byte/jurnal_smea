<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Model Siswa
 * Digunakan untuk mengelola data master siswa di sekolah.
 */
class Siswa extends Model
{
    // Mengaktifkan fitur hapus sementara (Soft Delete)
    use SoftDeletes;

    // Nama tabel di database
    protected $table = 'siswa';

    // Primary key tabel siswa adalah string NIS (bukan ID angka biasa)
    protected $primaryKey = 'nis';
    public $incrementing = false;
    protected $keyType = 'string';

    // Mematikan kolom timestamps bawaan Laravel
    public $timestamps = false;

    // Daftar kolom yang diizinkan diisi
    protected $fillable = [
        'nis',            // Nomor Induk Siswa (Primary Key)
        'nisn',           // Nomor Induk Siswa Nasional
        'nama_siswa',     // Nama lengkap siswa
        'id_kelas',       // ID kelas tempat siswa belajar
        'jenis_kelamin',  // Jenis kelamin (L/P)
        'no_hp_wali',     // Nomor WhatsApp/HP orang tua/wali murid
        'kota_lahir',     // Kota tempat lahir
        'tanggal_lahir',  // Tanggal lahir siswa
        'alamat',         // Alamat tempat tinggal siswa
        'alasan_hapus',   // Alasan jika data siswa dihapus (soft delete)
    ];

    /**
     * Relasi ke Model Kelas (Menyediakan akses ke data kelas siswa)
     */
    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'id_kelas', 'id_kelas');
    }
}
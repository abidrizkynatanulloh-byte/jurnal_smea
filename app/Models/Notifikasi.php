<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Notifikasi extends Model
{
    use SoftDeletes;

    protected $table = 'notifikasi';

    // Tabel notifikasi hanya memiliki created_at bawaan
    public $timestamps = false;

    protected $fillable = [
        'untuk_user_id',   // ID User penerima notifikasi (Wakasis Siswa)
        'judul',           // Judul notifikasi
        'pesan',           // Isi pesan detail
        'jenis',           // 'dispen_siswa', 'izin_guru', dll.
        'ref_id',          // ID data dispen_siswa terkait
        'sudah_dibaca',    // 0 = belum, 1 = sudah dibaca
        'created_at',
    ];

    /**
     * Relasi ke User penerima notifikasi.
     */
    public function penerima()
    {
        return $this->belongsTo(User::class, 'untuk_user_id', 'id');
    }
}
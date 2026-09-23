<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Model IzinSiswa
 * Digunakan untuk mengelola permohonan izin / sakit siswa yang diajukan oleh Orang Tua.
 * 
 * @property int $id
 * @property string $nis
 * @property string $kategori
 * @property string $alasan
 * @property string $tanggal_mulai
 * @property string $tanggal_selesai
 * @property string|null $bukti_foto
 * @property string $status
 * @property int|null $disetujui_oleh
 * @property string|null $catatan_penolakan
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property \Carbon\Carbon|null $deleted_at
 * @property-read \App\Models\Siswa|null $siswa
 * @property-read \App\Models\User|null $disetujuiOleh
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
        return $this->belongsTo(Siswa::class, 'nis', 'nis');
    }

    /**
     * Relasi ke User yang menyetujui izin ini (Guru Piket)
     */
    public function disetujuiOleh()
    {
        return $this->belongsTo(User::class, 'disetujui_oleh');
    }
}

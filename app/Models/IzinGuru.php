<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Model IzinGuru
 * Digunakan untuk mengelola permohonan izin/dinas/sakit guru serta alur persetujuannya.
 */
class IzinGuru extends Model
{
    // Mengaktifkan fitur hapus sementara (Soft Delete)
    use SoftDeletes;

    // Nama tabel di database
    protected $table = 'izin_guru';

    // Daftar kolom yang diizinkan diisi
    protected $fillable = [
        'id_guru',            // ID guru yang mengajukan izin
        'tanggal_mulai',      // Tanggal mulai izin
        'tanggal_selesai',    // Tanggal selesai izin
        'alasan',             // Jenis/alasan izin (Sakit, Dinas, Urusan Keluarga, dll)
        'keterangan',         // Detail/penjelasan alasan izin
        'bukti_foto',         // File foto bukti/surat izin
        'kelas_terdampak',    // Daftar kelas yang ditinggalkan saat izin
        'status_waka',        // Status persetujuan dari Waka Kurikulum/Kesiswaan
        'status_sdm',         // Status persetujuan SDM / Ketenagaan
        'status_piket',       // Status verifikasi dari Guru Piket
        'status_kepsek',      // Status persetujuan dari Kepala Sekolah
        'status_akhir',       // Status keputusan akhir: Diajukan, Disetujui, Ditolak
        'catatan_penolakan',  // Catatan alasan jika izin ditolak
    ];

    /**
     * Relasi ke data Guru (Setiap permohonan izin dimiliki oleh satu guru)
     */
    public function guru()
    {
        return $this->belongsTo(Guru::class, 'id_guru', 'id_guru');
    }

    /**
     * Cek jika seluruh role terkait (Waka, Piket, Kepsek) telah menyetujui, maka status_akhir menjadi 'Disetujui'.
     */
    public function cekDanUpdateStatusAkhir()
    {
        if ($this->status_waka === 'Ditolak' || $this->status_sdm === 'Ditolak' || $this->status_kepsek === 'Ditolak') {
            $this->status_akhir = 'Ditolak';
        } elseif ($this->status_waka === 'Disetujui' && $this->status_sdm === 'Disetujui' && $this->status_kepsek === 'Disetujui') {
            $this->status_akhir = 'Disetujui';
        } else {
            $this->status_akhir = 'Diajukan';
        }
        $this->save();
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Model DispenSiswa
 * Digunakan untuk mengelola data izin keluar / dispensasi siswa dari lingkungan sekolah.
 */
class DispenSiswa extends Model
{
    // Mengaktifkan fitur hapus sementara (tidak langsung hilang dari database)
    use SoftDeletes;

    // Nama tabel di database
    protected $table = 'dispen_siswa';

    // Daftar kolom yang diizinkan untuk diisi secara langsung
    protected $fillable = [
        'nis',                  // NIS siswa yang mengajukan dispen
        'keperluan',            // Alasan/keperluan dispen (misal: lomba, urusan keluarga)
        'jam_ke',               // Jam pelajaran ke-berapa (misal: Jam ke-2 s/d 4)
        'tanggal',              // Tanggal dispen dilaksanakan
        'jam_keluar_rencana',   // Estimasi waktu siswa keluar sekolah
        'jam_kembali_rencana',  // Estimasi waktu siswa kembali ke sekolah
        'jam_keluar_aktual',   // Jam nyata siswa melewati gerbang (dicatat satpam)
        'jam_kembali_aktual',  // Jam nyata siswa kembali (dicatat satpam)
        'status',               // Status dispen: Menunggu, Disetujui, Ditolak, Sedang di Luar, Sudah Kembali
        'disetujui_oleh',      // ID User (Waka/Piket) yang menyetujui dispen ini
        'catatan_wakasis',      // Catatan opsional dari Waka Kesiswaan
        'dicatat_satpam',      // ID User satpam yang mengonfirmasi siswa keluar
    ];

    /**
     * Relasi ke data Siswa (Satu data dispen milik satu siswa)
     */
    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'nis', 'nis');
    }

    /**
     * Relasi ke User yang menyetujui dispen ini (Guru Piket atau Waka)
     */
    public function disetujuiOleh()
    {
        return $this->belongsTo(User::class, 'disetujui_oleh');
    }

    /**
     * Relasi ke User Satpam yang mencatat siswa saat keluar/masuk gerbang
     */
    public function dicatatSatpam()
    {
        return $this->belongsTo(User::class, 'dicatat_satpam');
    }
}

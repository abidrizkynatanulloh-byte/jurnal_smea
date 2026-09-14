<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Model JurnalMengajar
 * Digunakan untuk mencatat aktivitas mengajar guru di kelas setiap jam pelajaran.
 */
class JurnalMengajar extends Model
{
    // Mengaktifkan fitur hapus sementara (Soft Delete)
    use SoftDeletes;

    // Nama tabel utama di database
    protected $table = 'jurnal_mengajar';

    // Kolom kunci utama (primary key) tabel
    protected $primaryKey = 'id_jurnal';

    // Mematikan kolom timestamps bawaan Laravel (created_at & updated_at)
    public $timestamps = false;

    // Daftar kolom yang diizinkan untuk diisi secara langsung
    protected $fillable = [
        'id_jadwal',              // ID jadwal KBM kelas terkait
        'tanggal',                // Tanggal pelaksanaan KBM
        'materi',                 // Judul/materi pelajaran yang disampaikan
        'status_kehadiran_guru',  // Status kehadiran guru (Hadir/Izin/Tugas)
        'catatan',                // Catatan tambahan mengenai jalannya KBM
        'dicatat_pada',           // Waktu saat jurnal dibuat
    ];

    /**
     * Relasi ke Jadwal Pelajaran (Satu jurnal milik satu jadwal KBM)
     */
    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class, 'id_jadwal', 'id_jadwal');
    }

    /**
     * Relasi ke Foto Mengajar (Satu jurnal memiliki satu foto dokumentasi)
     */
    public function foto()
    {
        return $this->hasOne(FotoMengajar::class, 'id_jurnal', 'id_jurnal');
    }

    /**
     * Relasi ke Detail Ketidakhadiran Siswa (Satu jurnal memiliki banyak catatan siswa yang absen/sakit/dispen)
     */
    public function detailKetidakhadiran()
    {
        return $this->hasMany(JurnalDetailKetidakhadiran::class, 'id_jurnal', 'id_jurnal');
    }
}
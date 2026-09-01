<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JurnalMengajar extends Model
{
    use SoftDeletes;

    protected $table = 'jurnal_mengajar';
    protected $primaryKey = 'id_jurnal';
    public $timestamps = false;

    protected $fillable = [
        'id_jadwal',
        'tanggal',
        'materi',
        'status_kehadiran_guru',
        'catatan',
        'dicatat_pada',
    ];

    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class, 'id_jadwal', 'id_jadwal');
    }
    // Relasi ke Foto Mengajar
    public function foto()
    {
        return $this->hasOne(FotoMengajar::class, 'id_jurnal', 'id_jurnal');
    }

    // Relasi ke Detail Ketidakhadiran Siswa
    public function detailKetidakhadiran()
    {
        return $this->hasMany(JurnalDetailKetidakhadiran::class, 'id_jurnal', 'id_jurnal');
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TugasKelasKosong extends Model
{
    use SoftDeletes;

    protected $table = 'tugas_kelas_kosong';

    protected $fillable = [
        'id_jadwal',
        'tanggal',
        'id_guru',
        'id_kelas',
        'deskripsi_tugas',
        'status',
    ];

    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class, 'id_jadwal', 'id_jadwal');
    }

    public function guru()
    {
        return $this->belongsTo(Guru::class, 'id_guru', 'id_guru');
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'id_kelas', 'id_kelas');
    }
}

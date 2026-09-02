<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SiswaTelat extends Model
{
    use SoftDeletes;

    protected $table = 'siswa_telat';

    protected $fillable = [
        'nis',
        'tanggal',
        'jam_terlambat',
        'alasan',
        'tindakan',
        'id_guru_piket',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'nis', 'nis');
    }

    public function guruPiket()
    {
        return $this->belongsTo(Guru::class, 'id_guru_piket', 'id_guru');
    }
}

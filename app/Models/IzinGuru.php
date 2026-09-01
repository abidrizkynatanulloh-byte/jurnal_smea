<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IzinGuru extends Model
{
    use SoftDeletes;

    protected $table = 'izin_guru';

    protected $fillable = [
        'id_guru',
        'tanggal_mulai',
        'tanggal_selesai',
        'alasan',
        'keterangan',
        'bukti_foto',
        'kelas_terdampak',
        'status_waka',
        'status_sdm',
        'status_kepsek',
        'status_akhir',
        'catatan_penolakan',
    ];

    public function guru()
    {
        return $this->belongsTo(Guru::class, 'id_guru', 'id_guru');
    }
}

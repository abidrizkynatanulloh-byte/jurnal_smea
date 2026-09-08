<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IzinSiswa extends Model
{
    use SoftDeletes;

    protected $table = 'izin_siswa';

    protected $fillable = [
        'nis',
        'kategori',
        'alasan',
        'tanggal_mulai',
        'tanggal_selesai',
        'bukti_foto',
        'status',
        'disetujui_oleh',
        'catatan_penolakan',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'nis', 'nis');
    }

    public function disetujuiOleh()
    {
        return $this->belongsTo(User::class, 'disetujui_oleh');
    }
}

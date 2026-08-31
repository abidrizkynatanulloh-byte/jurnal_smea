<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PengajuanIzinSiswa extends Model
{
    use SoftDeletes;

    protected $table = 'pengajuan_izin_siswa';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'nis',
        'jenis_izin',
        'tanggal',
        'keterangan',
        'foto_bukti',
        'status',
        'disetujui_oleh',
        'catatan_wali',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'nis', 'nis');
    }

    public function disetujuiOleh()
    {
        return $this->belongsTo(User::class, 'disetujui_oleh', 'id');
    }
}
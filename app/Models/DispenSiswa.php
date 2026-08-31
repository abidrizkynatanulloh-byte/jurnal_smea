<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DispenSiswa extends Model
{
    use SoftDeletes;

    protected $table = 'dispen_siswa';

    protected $fillable = [
        'nis',
        'keperluan',
        'tanggal',
        'jam_keluar_rencana',
        'jam_kembali_rencana',
        'jam_keluar_aktual',
        'jam_kembali_aktual',
        'status',
        'disetujui_oleh',
        'catatan_wakasis',
        'dicatat_satpam',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'nis', 'nis');
    }

    public function disetujuiOleh()
    {
        return $this->belongsTo(User::class, 'disetujui_oleh');
    }

    public function dicatatSatpam()
    {
        return $this->belongsTo(User::class, 'dicatat_satpam');
    }
}

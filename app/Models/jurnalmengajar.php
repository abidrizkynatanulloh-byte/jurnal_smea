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
}
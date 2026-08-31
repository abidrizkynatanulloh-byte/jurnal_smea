<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JurnalDetailKetidakhadiran extends Model
{
    use SoftDeletes;

    protected $table = 'jurnal_detail_ketidakhadiran';
    protected $primaryKey = 'id_detail';
    public $timestamps = false;

    protected $fillable = [
        'id_jurnal',
        'id_siswa',
        'keterangan',
        'ref_izin_id',
        'dicatat_oleh',
    ];

    public function jurnalMengajar()
    {
        return $this->belongsTo(JurnalMengajar::class, 'id_jurnal', 'id_jurnal');
    }
}
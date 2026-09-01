<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FotoMengajar extends Model
{
    use SoftDeletes;

    protected $table = 'foto_mengajar';
    protected $primaryKey = 'id_foto';
    public $timestamps = false; // Karena kita pakai field manual diambil_pada

    protected $fillable = [
        'id_jurnal',
        'foto_path',
        'diambil_pada',
        'keterangan',
    ];

    public function jurnal()
    {
        return $this->belongsTo(JurnalMengajar::class, 'id_jurnal', 'id_jurnal');
    }
}
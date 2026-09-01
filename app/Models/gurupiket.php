<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GuruPiket extends Model
{
    use SoftDeletes;

    protected $table    = 'guru_piket';
    protected $primaryKey = 'id_piket';
    public $timestamps  = true;
    const UPDATED_AT = null;

    protected $fillable = [
        'id_guru',
        'hari',
        'tanggal_khusus',
        'shift',
    ];

    /**
     * Relasi ke Guru.
     */
    public function guru()
    {
        return $this->belongsTo(Guru::class, 'id_guru', 'id_guru');
    }
}
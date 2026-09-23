<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Model GuruPiket
 * 
 * @property int $id_piket
 * @property int $id_guru
 * @property string|null $hari
 * @property string|null $tanggal_khusus
 * @property string $shift
 * @property string $peran_piket
 * @property string|null $keterangan
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $deleted_at
 * @property-read \App\Models\Guru|null $guru
 */
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
        'peran_piket',
        'keterangan',
    ];

    /**
     * Relasi ke Guru.
     */
    public function guru()
    {
        return $this->belongsTo(Guru::class, 'id_guru', 'id_guru');
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Mapel extends Model
{
    use SoftDeletes;

    protected $table = 'mapel';
    protected $primaryKey = 'kode_mapel';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = ['kode_mapel', 'nama_mapel'];

    /**
     * Relasi ke Jadwal Mengajar (satu mapel bisa diajar di banyak jadwal).
     */
    public function jadwal()
    {
        return $this->hasMany(Jadwal::class, 'kode_mapel', 'kode_mapel');
    }
}
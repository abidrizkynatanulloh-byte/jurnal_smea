<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JamPelajaran extends Model
{
    use SoftDeletes;

    protected $table = 'jam_pelajaran';
    protected $primaryKey = 'id_jam';

    protected $fillable = [
        'jam_ke',
        'waktu_mulai',
        'waktu_selesai',
    ];
}
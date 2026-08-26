<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StafTu extends Model
{
    use SoftDeletes;

    protected $table = 'staf_tu';
    protected $primaryKey = 'id_staf';

    // Menggunakan timestamps (created_at & updated_at)
    public $timestamps = true;

    protected $fillable = [
        'nip',
        'nama_staf',
        'jabatan',
        'no_hp',
    ];
}
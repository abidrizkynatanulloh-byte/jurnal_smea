<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Satpam extends Model
{
    use SoftDeletes;

    protected $table = 'satpam';
    protected $primaryKey = 'id_satpam';

    public $timestamps = true;

    protected $fillable = [
        'usn',
        'nama_satpam',
        'no_hp',
    ];
}
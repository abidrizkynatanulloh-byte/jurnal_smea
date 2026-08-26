<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Siswa extends Model
{
    use SoftDeletes;

    protected $table = 'siswa';

    // Primary key tabel siswa adalah string (nis)
    protected $primaryKey = 'nis';
    public $incrementing = false;
    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = [
        'nis',
        'nisn',
        'nama_siswa',
        'id_kelas',
    ];
}
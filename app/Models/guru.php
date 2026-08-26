<?php

namespace App\Models;

// Mengimpor kelas Model bawaan Laravel
use Illuminate\Database\Eloquent\Model;
// Mengimpor trait SoftDeletes
use Illuminate\Database\Eloquent\SoftDeletes;

class Guru extends Model
{
    use SoftDeletes;

    // Nama tabel di database
    protected $table = 'guru';

    // Primary key tabel guru
    protected $primaryKey = 'id_guru';

    // Tabel guru asli tidak memiliki kolom created_at & updated_at bawaan
    public $timestamps = false;

    // Kolom yang boleh diisi
    protected $fillable = [
        'nip',
        'nama_guru',
        'no_hp',
        'kode_mapel',
        'jabatan',
        'email',
        'foto_profil',
    ];
}
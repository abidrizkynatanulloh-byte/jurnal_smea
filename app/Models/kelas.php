<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kelas extends Model
{
    use SoftDeletes;

    protected $table = 'kelas';
    protected $primaryKey = 'id_kelas';
    public $timestamps = false;

    protected $fillable = ['nama_kelas', 'wali_kelas', 'jumlah_siswa'];

    protected static function booted()
    {
        static::addGlobalScope('excludeDummy', function ($builder) {
            $builder->where('nama_kelas', '!=', 'Kelas_49');
        });
    }

    public function waliKelasGuru()
    {
        return $this->belongsTo(Guru::class, 'wali_kelas', 'nip');
    }

    public function getWaliKelasGuruDataAttribute()
    {
        if (empty($this->wali_kelas)) return null;
        return Guru::where('nip', $this->wali_kelas)
            ->orWhere('id_guru', $this->wali_kelas)
            ->orWhere('nama_guru', $this->wali_kelas)
            ->first();
    }
}
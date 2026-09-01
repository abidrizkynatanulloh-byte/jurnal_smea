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

    /**
     * Cek apakah guru ini terdaftar sebagai wali kelas dari kelas manapun.
     */
    public function isWaliKelas()
    {
        return \App\Models\Kelas::where('wali_kelas', $this->nip)
            ->orWhere('wali_kelas', $this->id_guru)
            ->orWhere('wali_kelas', $this->nama_guru)
            ->exists();
    }

    /**
     * Cek apakah guru ini bertugas sebagai guru piket hari ini.
     */
    public function isPiketHariIni()
    {
        $hariMap = [
            'Monday'    => 'Senin',
            'Tuesday'   => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday'  => 'Kamis',
            'Friday'    => 'Jumat',
            'Saturday'  => 'Sabtu',
            'Sunday'    => 'Minggu',
        ];
        $namaHariIni = $hariMap[\Carbon\Carbon::now()->format('l')] ?? 'Senin';
        $tanggalHari = \Carbon\Carbon::today()->toDateString();

        return \App\Models\GuruPiket::where('id_guru', $this->id_guru)
            ->where(function ($q) use ($namaHariIni, $tanggalHari) {
                $q->where('hari', $namaHariIni)
                  ->orWhere('tanggal_khusus', $tanggalHari);
            })
            ->whereNull('deleted_at')
            ->exists();
    }
}
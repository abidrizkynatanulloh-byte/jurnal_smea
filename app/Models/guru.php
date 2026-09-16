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
        'alasan_hapus',
    ];

    /**
     * Relasi ke akun User (satu guru memiliki satu akun user).
     */
    public function user()
    {
        return $this->hasOne(User::class, 'id_guru', 'id_guru');
    }

    /**
     * Relasi ke Penugasan Guru Piket.
     */
    public function piketAssignments()
    {
        return $this->hasMany(GuruPiket::class, 'id_guru', 'id_guru');
    }

    /**
     * Relasi ke Jadwal Mengajar (satu guru memiliki banyak jadwal).
     */
    public function jadwal()
    {
        return $this->hasMany(Jadwal::class, 'id_guru', 'id_guru');
    }

    public function jadwals()
    {
        return $this->hasMany(Jadwal::class, 'id_guru', 'id_guru');
    }

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
     * Cek apakah guru ini bertugas sebagai guru piket hari ini & pada shift jam saat ini.
     */
    public function isPiketHariIni($checkShift = true)
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
        $jamSekarang  = \Carbon\Carbon::now()->format('H:i:s');

        $query = \App\Models\GuruPiket::where('id_guru', $this->id_guru)
            ->where(function ($q) use ($namaHariIni, $tanggalHari) {
                $q->where('hari', $namaHariIni)
                  ->orWhere('tanggal_khusus', $tanggalHari);
            })
            ->whereNull('deleted_at');

        if ($checkShift) {
            // Sebelum 11:00 WIB -> Shift Pagi (07.00 - 11.00)
            if ($jamSekarang < '11:00:00') {
                $query->where(function ($q) {
                    $q->where('shift', 'Pagi')
                      ->orWhere('peran_piket', 'Piket Waka')
                      ->orWhereNull('shift');
                });
            } else {
                // Jam 11:00 WIB ke atas -> Shift Siang (11.00 - 15.00)
                $query->where(function ($q) {
                    $q->where('shift', 'Siang')
                      ->orWhere('peran_piket', 'Piket Waka')
                      ->orWhereNull('shift');
                });
            }
        }

        return $query->exists();
    }
}
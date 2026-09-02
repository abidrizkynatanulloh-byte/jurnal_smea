<?php

namespace App\Models;

// Mengimpor trait untuk fitur soft delete
use Illuminate\Database\Eloquent\SoftDeletes;
// Mengimpor kelas dasar User bawaan Laravel untuk autentikasi
use Illuminate\Foundation\Auth\User as Authenticatable;
// Mengimpor trait notifikasi bawaan Laravel
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    // SoftDeletes: Data yang dihapus tidak langsung hilang permanen (mengisi kolom deleted_at)
    // Notifiable: Memungkinkan model user ini menerima notifikasi sistem
    use Notifiable, SoftDeletes;

    /**
     * Nama tabel di database MySQL yang digunakan oleh model ini.
     */
    protected $table = 'users';

    /**
     * Kolom-kolom yang diizinkan untuk diisi secara langsung (mass assignment).
     */
    protected $fillable = [
        'username',    // NIP (Guru/Staf/Kepsek/Wakasis), NISN (Wali Murid), atau USN (Satpam)
        'password',    // Password terenkripsi Bcrypt
        'role',        // ENUM peran akun
        'id_guru',     // Foreign Key ke tabel 'guru'
        'id_staf',     // Foreign Key ke tabel 'staf_tu'
        'id_satpam',   // Foreign Key ke tabel 'satpam'
        'nisn_siswa',  // Foreign Key ke kolom 'nisn' pada tabel 'siswa'
        'is_active',   // Status akun: 1 (aktif), 0 (nonaktif)
    ];

    /**
     * Kolom yang disembunyikan agar tidak bocor saat data diubah menjadi Array/JSON.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Konversi tipe data otomatis saat data dibaca/ditulis.
     */
    protected function casts(): array
    {
        return [
            'password'  => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Accessor untuk nama tampilan pengguna (agar tidak ada yang kosong)
     */
    public function getNamaDisplayAttribute(): string
    {
        if ($this->role === 'guru' || $this->role === 'guru_piket' || $this->role === 'kepala_sekolah' || $this->role === 'wakasis_siswa' || $this->role === 'wakasis_guru') {
            if ($this->guru && $this->guru->nama_guru) {
                return $this->guru->nama_guru;
            }
        }
        if ($this->role === 'staf_tu') {
            if ($this->stafTu && $this->stafTu->nama_staf) {
                return $this->stafTu->nama_staf;
            }
        }
        if ($this->role === 'satpam') {
            if ($this->satpam && $this->satpam->nama_satpam) {
                return $this->satpam->nama_satpam;
            }
        }
        if ($this->role === 'wali_murid') {
            if ($this->siswa && $this->siswa->nama_siswa) {
                return 'Wali dari ' . $this->siswa->nama_siswa;
            }
        }

        // Fallbacks
        if ($this->guru) return $this->guru->nama_guru;
        if ($this->stafTu) return $this->stafTu->nama_staf;
        if ($this->satpam) return $this->satpam->nama_satpam;
        if ($this->siswa) return 'Wali dari ' . $this->siswa->nama_siswa;

        if ($this->username === '0000001') return 'Administrator TU';
        return ucfirst(str_replace('_', ' ', $this->role ?? 'Pengguna')) . ' (' . $this->username . ')';
    }

    /**
     * Relasi ke data Guru (untuk role: guru, guru_piket, kepsek, wakasis).
     */
    public function guru()
    {
        return $this->belongsTo(Guru::class, 'id_guru', 'id_guru');
    }

    /**
     * Relasi ke data Staf TU (untuk role: staf_tu / admin).
     */
    public function stafTu()
    {
        return $this->belongsTo(StafTu::class, 'id_staf', 'id_staf');
    }

    /**
     * Relasi ke data Satpam (untuk role: satpam).
     */
    public function satpam()
    {
        return $this->belongsTo(Satpam::class, 'id_satpam', 'id_satpam');
    }

    /**
     * Relasi ke data Siswa (untuk role: wali_murid).
     */
    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'nisn_siswa', 'nisn');
    }
}
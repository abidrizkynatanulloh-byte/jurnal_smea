<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model AuditLog
 * Digunakan untuk mencatat setiap aktivitas penting yang dilakukan pengguna di dalam sistem (log jejak aktivitas).
 */
class AuditLog extends Model
{
    // Nama tabel di database
    protected $table = 'audit_logs';

    // Mematikan pencatatan timestamps otomatis dari Laravel
    public $timestamps = false;

    // Daftar kolom yang diizinkan untuk diisi
    protected $fillable = [
        'user_id',      // ID pengguna yang melakukan aktivitas
        'action',       // Nama/jenis tindakan yang dilakukan (misal: 'Persetujuan Dispen')
        'description',  // Rincian/keterangan aktivitas
        'ip_address',   // Alamat IP perangkat pengguna saat melakukan aktivitas
        'created_at',   // Waktu pencatatan log
    ];

    /**
     * Relasi ke User (Setiap catatan log dimiliki oleh satu pengguna)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Helper fungsi cepat untuk langsung mencatat log aktivitas di mana saja
     */
    public static function log($action, $description = null, $userId = null)
    {
        try {
            return self::create([
                'user_id'     => $userId ?? auth()->id(), // Pakai ID user yang login jika tidak ditentukan
                'action'      => $action,
                'description' => $description,
                'ip_address'  => request()->ip(),
                'created_at'  => now(),
            ]);
        } catch (\Exception $e) {
            // Abaikan jika terjadi error agar tidak mengganggu alur utama aplikasi
            return null;
        }
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    protected $table = 'audit_logs';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'action',
        'description',
        'ip_address',
        'created_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Helper cepat untuk mencatat aktivitas sistem
     */
    public static function log($action, $description = null, $userId = null)
    {
        try {
            return self::create([
                'user_id'     => $userId ?? auth()->id(),
                'action'      => $action,
                'description' => $description,
                'ip_address'  => request()->ip(),
                'created_at'  => now(),
            ]);
        } catch (\Exception $e) {
            // Ignore failure if table is not yet migrated
            return null;
        }
    }
}

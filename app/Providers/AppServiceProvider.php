<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (request()->server->has('HTTP_X_FORWARDED_PROTO') && request()->server->get('HTTP_X_FORWARDED_PROTO') === 'https') {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        try {
            \Illuminate\Support\Facades\DB::table('kelas')->where('nama_kelas', 'LIKE', '%Kelas_49%')->delete();
        } catch (\Throwable $e) {}

        // Injeksi data Audit Log terbaru secara otomatis ke layout utama untuk SEMUA ROLE
        \Illuminate\Support\Facades\View::composer('layouts.app', function ($view) {
            if (\Illuminate\Support\Facades\Auth::check()) {
                try {
                    $globalAuditLogs = \App\Models\AuditLog::with('user')
                        ->orderBy('id', 'desc')
                        ->take(20)
                        ->get();
                    $view->with('globalAuditLogs', $globalAuditLogs);
                } catch (\Throwable $e) {
                    $view->with('globalAuditLogs', collect());
                }
            }
        });
    }
}

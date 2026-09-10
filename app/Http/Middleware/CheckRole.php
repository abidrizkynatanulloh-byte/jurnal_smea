<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Jika role user ada di daftar role yang diizinkan
        if (in_array($user->role, $roles)) {
            return $next($request);
        }

        // Tentukan rute dashboard asal sesuai role user yang sedang aktif
        $defaultRoute = match ($user->role) {
            'staf_tu'        => 'admin.dashboard',
            'guru'           => 'guru.dashboard',
            'guru_piket'     => 'piket.dashboard',
            'wali_murid'     => 'wali.dashboard',
            'satpam'         => 'satpam.dashboard',
            'kepala_sekolah' => 'kepsek.dashboard',
            'wakasis_siswa'  => 'wakasis.siswa.dashboard',
            'wakasis_guru'   => 'wakasis.guru.dashboard',
            default          => 'login',
        };

        $roleLabels = [
            'staf_tu'        => 'STAF TU (Admin)',
            'kepala_sekolah' => 'Kepala Sekolah',
            'wakasis_guru'   => 'Waka Kurikulum & SDM',
            'wakasis_siswa'  => 'Waka Kesiswaan',
            'guru_piket'     => 'Guru Piket',
            'guru'           => 'Guru',
            'satpam'         => 'Satpam',
            'wali_murid'     => 'Wali Murid',
        ];

        $userRoleLabel = $roleLabels[$user->role] ?? $user->role;
        $targetRoleLabels = array_map(fn($r) => $roleLabels[$r] ?? $r, $roles);
        $targetStr = implode(' / ', $targetRoleLabels);

        return redirect()->route($defaultRoute)->with(
            'warning',
            "Halaman tersebut khusus untuk {$targetStr}. Anda saat ini sedang login sebagai {$userRoleLabel}. Silakan logout terlebih dahulu jika ingin masuk sebagai {$targetStr}."
        );
    }
}

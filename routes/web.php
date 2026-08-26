<?php

// Mengimpor AuthController
use App\Http\Controllers\AuthController;
// Mengimpor Facade Route
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - JurnalEsemkita
|--------------------------------------------------------------------------
*/

// Redirect URL utama (/) langsung ke halaman login
Route::get('/', function () {
    // Jika sudah login, arahkan ke dashboard admin (atau dashboard role-nya)
    if (Auth::check()) {
        return redirect()->route('admin.dashboard');
    }

    // Jika belum login, barulah diarahkan ke form login
    return redirect()->route('login');
});

// =========================================================================
// Rute untuk Tamu / Belum Login (Guest)
// =========================================================================
Route::middleware('guest')->group(function () {
    // Menampilkan halaman login
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');

    // Menerima kiriman form login
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

// =========================================================================
// Rute untuk Pengguna yang Sudah Login (Auth)
// =========================================================================
Route::middleware('auth')->group(function () {
    // Logout sistem
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Tempat mendaftarkan rute dashboard masing-masing role nanti saat siap:
    // Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    // Route::get('/guru/dashboard', [GuruController::class, 'index'])->name('guru.dashboard');
        Route::get('/admin/dashboard', function () {
        return view ('admin.dashboard');
    })->name('admin.dashboard');
});
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
        // Kelola User 1 Halaman (Tampil + Simpan)
    Route::get('/admin/users', [\App\Http\Controllers\Admin\UserController::class, 'index'])->name('admin.users.index');
    Route::post('/admin/users', [\App\Http\Controllers\Admin\UserController::class, 'store'])->name('admin.users.store');

        // Dashboard & Form Input Dispen Guru Piket
    Route::get('/piket/dashboard', [\App\Http\Controllers\PiketController::class, 'index'])->name('piket.dashboard');
    Route::post('/piket/dispen', [\App\Http\Controllers\PiketController::class, 'storeDispen'])->name('piket.dispen.store');
});
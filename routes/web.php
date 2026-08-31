<?php

// -------------------------------------------------------------------------
// Import Controller
// -------------------------------------------------------------------------
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PiketController;
use App\Http\Controllers\WakasisSiswaController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\GuruController;
use App\Http\Controllers\Admin\SiswaController;
use App\Http\Controllers\Admin\MapelController;
use App\Http\Controllers\Admin\JamPelajaranController;
use App\Http\Controllers\Admin\JadwalController;
use App\Http\Controllers\Admin\RekapJurnalController;
use App\Http\Controllers\Guru\GuruDashboardController;
use App\Http\Controllers\Guru\JurnalController;

// Import Facade Laravel
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - JurnalEsemkita
|--------------------------------------------------------------------------
*/

// Redirect Halaman Utama (/)
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('login');
});

// =========================================================================
// RUTE GUEST (Khusus yang Belum Login)
// =========================================================================
Route::middleware('guest')->group(function () {
    Route::get('/login',  [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

// =========================================================================
// RUTE AUTH (Khusus Pengguna yang Sudah Login)
// =========================================================================
Route::middleware('auth')->group(function () {

    // Logout Sistem
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // ---------------------------------------------------------------------
    // 1. DASHBOARD UTAMA TATA USAHA (ADMIN)
    // ---------------------------------------------------------------------
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    // ---------------------------------------------------------------------
    // 2. DATA GURU & PEGAWAI
    // ---------------------------------------------------------------------
    Route::get('/admin/guru',                    [GuruController::class, 'index']  )->name('admin.guru.index');
    Route::post('/admin/guru',                   [GuruController::class, 'store']  )->name('admin.guru.store');
    Route::delete('/admin/guru/{id}',            [GuruController::class, 'destroy'])->name('admin.guru.destroy');
    Route::get('/admin/guru/trash',              [GuruController::class, 'trash']  )->name('admin.guru.trash');
    Route::post('/admin/guru/{id}/restore',      [GuruController::class, 'restore'])->name('admin.guru.restore');

    // ---------------------------------------------------------------------
    // 3. DATA SISWA
    // ---------------------------------------------------------------------
    Route::get('/admin/siswa',                   [SiswaController::class, 'index']  )->name('admin.siswa.index');
    Route::post('/admin/siswa',                  [SiswaController::class, 'store']  )->name('admin.siswa.store');
    Route::delete('/admin/siswa/{nis}',          [SiswaController::class, 'destroy'])->name('admin.siswa.destroy');
    Route::get('/admin/siswa/trash',             [SiswaController::class, 'trash']  )->name('admin.siswa.trash');
    Route::post('/admin/siswa/{nis}/restore',    [SiswaController::class, 'restore'])->name('admin.siswa.restore');

    // ---------------------------------------------------------------------
    // 4. DATA MATA PELAJARAN
    // ---------------------------------------------------------------------
    Route::get('/admin/mapel',                   [MapelController::class, 'index']  )->name('admin.mapel.index');
    Route::post('/admin/mapel',                  [MapelController::class, 'store']  )->name('admin.mapel.store');
    Route::delete('/admin/mapel/{kode}',         [MapelController::class, 'destroy'])->name('admin.mapel.destroy');

    // ---------------------------------------------------------------------
    // 5. MASTER JAM PELAJARAN
    // ---------------------------------------------------------------------
    Route::get('/admin/jam-pelajaran',           [JamPelajaranController::class, 'index']  )->name('admin.jam.index');
    Route::post('/admin/jam-pelajaran',          [JamPelajaranController::class, 'store']  )->name('admin.jam.store');
    Route::put('/admin/jam-pelajaran/{id}',      [JamPelajaranController::class, 'update'] )->name('admin.jam.update');
    Route::delete('/admin/jam-pelajaran/{id}',   [JamPelajaranController::class, 'destroy'])->name('admin.jam.destroy');

    // ---------------------------------------------------------------------
    // 6. JADWAL MENGAJAR (ADMIN)
    // ---------------------------------------------------------------------
    Route::get('/admin/jadwal',                  [JadwalController::class, 'index']  )->name('admin.jadwal.index');
    Route::post('/admin/jadwal',                 [JadwalController::class, 'store']  )->name('admin.jadwal.store');
    Route::delete('/admin/jadwal/{id}',          [JadwalController::class, 'destroy'])->name('admin.jadwal.destroy');

    // ---------------------------------------------------------------------
    // 7. REKAP JURNAL & KEHADIRAN (ADMIN)
    // ---------------------------------------------------------------------
    Route::get('/admin/rekap-jurnal',            [RekapJurnalController::class, 'index'])->name('admin.rekap.index');

    // ---------------------------------------------------------------------
    // 8. KELOLA USER & PENGGUNA (ADMIN)
    // ---------------------------------------------------------------------
    Route::get('/admin/users',                   [UserController::class, 'index']  )->name('admin.users.index');
    Route::post('/admin/users',                  [UserController::class, 'store']  )->name('admin.users.store');
    Route::get('/admin/users/{id}/edit',         [UserController::class, 'edit']   )->name('admin.users.edit');
    Route::put('/admin/users/{id}',              [UserController::class, 'update'] )->name('admin.users.update');
    Route::delete('/admin/users/{id}',           [UserController::class, 'destroy'])->name('admin.users.destroy');

    // ---------------------------------------------------------------------
    // 9. GURU PIKET
    // ---------------------------------------------------------------------
    Route::get('/piket/dashboard',               [PiketController::class, 'index']      )->name('piket.dashboard');
    Route::post('/piket/dispen',                 [PiketController::class, 'storeDispen'])->name('piket.dispen.store');

    // ---------------------------------------------------------------------
    // 10. WAKIL KESISWAAN (SISWA)
    // ---------------------------------------------------------------------
    Route::get('/wakasis-siswa/dashboard',                       [WakasisSiswaController::class, 'index']  )->name('wakasis.siswa.dashboard');
    Route::post('/wakasis-siswa/dispen/{id}/approve',            [WakasisSiswaController::class, 'approve'])->name('wakasis.siswa.dispen.approve');
    Route::post('/wakasis-siswa/dispen/{id}/reject',             [WakasisSiswaController::class, 'reject'] )->name('wakasis.siswa.dispen.reject');

    // ---------------------------------------------------------------------
    // 11. DASHBOARD & FITUR GURU
    // ---------------------------------------------------------------------
    Route::get('/guru/dashboard',                [GuruDashboardController::class, 'index'])->name('guru.dashboard');

    // Jurnal Mengajar
    Route::get('/guru/jurnal/rekap',             [JurnalController::class, 'rekap'] )->name('guru.jurnal.rekap');
    Route::get('/guru/jurnal/input/{id_jadwal}', [JurnalController::class, 'create'])->name('guru.jurnal.create');
    Route::post('/guru/jurnal',                  [JurnalController::class, 'store'] )->name('guru.jurnal.store');
    Route::get('/guru/jurnal/{id_jurnal}',       [JurnalController::class, 'show']  )->name('guru.jurnal.show');

});
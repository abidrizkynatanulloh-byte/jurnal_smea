<?php

// -------------------------------------------------------------------------
// Import Controllers
// -------------------------------------------------------------------------
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PiketController;
use App\Http\Controllers\WakasisSiswaController;
use App\Http\Controllers\WakasisGuruController;
use App\Http\Controllers\SatpamController;
use App\Http\Controllers\KepsekController;
use App\Http\Controllers\OrtuController;
use App\Http\Controllers\IzinGuruController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\GuruController;
use App\Http\Controllers\Admin\SiswaController;
use App\Http\Controllers\Admin\MapelController;
use App\Http\Controllers\Admin\JamPelajaranController;
use App\Http\Controllers\Admin\JadwalController;
use App\Http\Controllers\Admin\RekapJurnalController;
use App\Http\Controllers\Admin\AdminGuruPiketController;
use App\Http\Controllers\Guru\GuruDashboardController;
use App\Http\Controllers\Guru\JurnalController;

// Import Facade Laravel
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - JurnalEsemkita (Sistem Jurnal & Monitoring Sekolah)
|--------------------------------------------------------------------------
*/

// Redirect Halaman Utama (/)
Route::get('/', function () {
    if (Auth::check()) {
        $user = Auth::user();
        return match ($user->role) {
            'staf_tu'        => redirect()->route('admin.dashboard'),
            'guru'           => redirect()->route('guru.dashboard'),
            'guru_piket'     => redirect()->route('piket.dashboard'),
            'wali_murid'     => redirect()->route('wali.dashboard'),
            'satpam'         => redirect()->route('satpam.dashboard'),
            'kepala_sekolah' => redirect()->route('kepsek.dashboard'),
            'wakasis_siswa'  => redirect()->route('wakasis.siswa.dashboard'),
            'wakasis_guru'   => redirect()->route('wakasis.guru.dashboard'),
            default          => redirect()->route('admin.dashboard'),
        };
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
    Route::get('/admin/jam-pelajaran',                        [JamPelajaranController::class, 'index']      )->name('admin.jam.index');
    Route::post('/admin/jam-pelajaran',                       [JamPelajaranController::class, 'store']      )->name('admin.jam.store');
    Route::put('/admin/jam-pelajaran/{id}',                   [JamPelajaranController::class, 'update']     )->name('admin.jam.update');
    Route::delete('/admin/jam-pelajaran/{id}',                [JamPelajaranController::class, 'destroy']    )->name('admin.jam.destroy');
    Route::post('/admin/jam-pelajaran/{id}/nonaktifkan',      [JamPelajaranController::class, 'nonaktifkan'])->name('admin.jam.nonaktifkan');
    Route::post('/admin/jam-pelajaran/{id}/aktifkan',         [JamPelajaranController::class, 'aktifkan']   )->name('admin.jam.aktifkan');

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
    Route::get('/admin/rekap-jurnal/{id}',       [RekapJurnalController::class, 'show'] )->name('admin.rekap.show');

    // ---------------------------------------------------------------------
    // 8. KELOLA USER & PENGGUNA (ADMIN)
    // ---------------------------------------------------------------------
    Route::get('/admin/users',                   [UserController::class, 'index']  )->name('admin.users.index');
    Route::post('/admin/users',                  [UserController::class, 'store']  )->name('admin.users.store');
    Route::get('/admin/users/{id}/edit',         [UserController::class, 'edit']   )->name('admin.users.edit');
    Route::put('/admin/users/{id}',              [UserController::class, 'update'] )->name('admin.users.update');
    Route::delete('/admin/users/{id}',           [UserController::class, 'destroy'])->name('admin.users.destroy');

    // Kelola Guru Piket (Admin)
    Route::get('/admin/guru-piket',              [AdminGuruPiketController::class, 'index']  )->name('admin.guru-piket.index');
    Route::post('/admin/guru-piket',             [AdminGuruPiketController::class, 'store']  )->name('admin.guru-piket.store');
    Route::delete('/admin/guru-piket/{id}',      [AdminGuruPiketController::class, 'destroy'])->name('admin.guru-piket.destroy');

    // ---------------------------------------------------------------------
    // 9. GURU PIKET (DISPEN & MONITORING KELAS & SISWA TELAT)
    // ---------------------------------------------------------------------
    Route::get('/piket/dashboard',               [PiketController::class, 'index']          )->name('piket.dashboard');
    Route::post('/piket/dispen',                 [PiketController::class, 'storeDispen']    )->name('piket.dispen.store');
    Route::post('/piket/siswa-telat',            [PiketController::class, 'storeSiswaTelat'])->name('piket.siswa-telat.store');
    Route::get('/piket/monitoring-kelas',        [PiketController::class, 'monitoringKelas'])->name('piket.monitoring-kelas');
    Route::post('/piket/tugas-kelas',            [PiketController::class, 'storeTugasKelas'])->name('piket.tugas-kelas.store');
    Route::post('/piket/izin-guru/{id}/approve', [PiketController::class, 'approveIzinGuru'])->name('piket.izin-guru.approve');
    Route::post('/piket/izin-guru/{id}/reject',  [PiketController::class, 'rejectIzinGuru'] )->name('piket.izin-guru.reject');

    // ---------------------------------------------------------------------
    // 10. WAKIL KESISWAAN (DISPEN SISWA)
    // ---------------------------------------------------------------------
    Route::get('/wakasis-siswa/dashboard',            [WakasisSiswaController::class, 'index']  )->name('wakasis.siswa.dashboard');
    Route::post('/wakasis-siswa/dispen/{id}/approve', [WakasisSiswaController::class, 'approve'])->name('wakasis.siswa.dispen.approve');
    Route::post('/wakasis-siswa/dispen/{id}/reject',  [WakasisSiswaController::class, 'reject'] )->name('wakasis.siswa.dispen.reject');

    // ---------------------------------------------------------------------
    // 11. WAKA KURIKULUM & SDM (IZIN GURU)
    // ---------------------------------------------------------------------
    Route::get('/wakasis-guru/dashboard',                 [WakasisGuruController::class, 'index']       )->name('wakasis.guru.dashboard');
    Route::post('/wakasis-guru/izin/{id}/approve-waka',   [WakasisGuruController::class, 'approveWaka'] )->name('wakasis.guru.approve.waka');
    Route::post('/wakasis-guru/izin/{id}/reject-waka',    [WakasisGuruController::class, 'rejectWaka']  )->name('wakasis.guru.reject.waka');
    Route::post('/wakasis-guru/izin/{id}/approve-sdm',    [WakasisGuruController::class, 'approveSdm']  )->name('wakasis.guru.approve.sdm');
    Route::post('/wakasis-guru/izin/{id}/reject-sdm',     [WakasisGuruController::class, 'rejectSdm']   )->name('wakasis.guru.reject.sdm');

    // ---------------------------------------------------------------------
    // 12. DASHBOARD & FITUR GURU
    // ---------------------------------------------------------------------
    Route::get('/guru/dashboard',                [GuruDashboardController::class, 'index'])->name('guru.dashboard');
    Route::get('/guru/wali-kelas',               [GuruDashboardController::class, 'waliKelas'])->name('guru.wali-kelas');

    // Jurnal Mengajar
    Route::get('/guru/jurnal/rekap',             [JurnalController::class, 'rekap'] )->name('guru.jurnal.rekap');
    Route::get('/guru/jurnal/input/{id_jadwal}', [JurnalController::class, 'create'])->name('guru.jurnal.create');
    Route::post('/guru/jurnal',                  [JurnalController::class, 'store'] )->name('guru.jurnal.store');
    Route::get('/guru/jurnal/{id_jurnal}',       [JurnalController::class, 'show']  )->name('guru.jurnal.show');

    // Izin Guru
    Route::get('/guru/izin',                     [IzinGuruController::class, 'index'] )->name('guru.izin.index');
    Route::get('/guru/izin/create',              [IzinGuruController::class, 'create'])->name('guru.izin.create');
    Route::post('/guru/izin',                    [IzinGuruController::class, 'store'] )->name('guru.izin.store');

    // ---------------------------------------------------------------------
    // 13. SATPAM (POS KEAMANAN & GERBANG)
    // ---------------------------------------------------------------------
    Route::get('/satpam/dashboard',              [SatpamController::class, 'index']           )->name('satpam.dashboard');
    Route::post('/satpam/dispen/{id}/keluar',    [SatpamController::class, 'konfirmasiKeluar'])->name('satpam.dispen.keluar');
    Route::post('/satpam/dispen/{id}/kembali',   [SatpamController::class, 'konfirmasiKembali'])->name('satpam.dispen.kembali');

    // ---------------------------------------------------------------------
    // 14. KEPALA SEKOLAH (EXECUTIVE MONITORING & TAHAP FINAL)
    // ---------------------------------------------------------------------
    Route::get('/kepsek/dashboard',                    [KepsekController::class, 'index']          )->name('kepsek.dashboard');
    Route::post('/kepsek/izin-guru/{id}/approve',      [KepsekController::class, 'approveIzinGuru'])->name('kepsek.izin-guru.approve');
    Route::post('/kepsek/izin-guru/{id}/reject',       [KepsekController::class, 'rejectIzinGuru'] )->name('kepsek.izin-guru.reject');

    // ---------------------------------------------------------------------
    // 15. ORANG TUA / WALI MURID
    // ---------------------------------------------------------------------
    Route::get('/ortu/dashboard', [OrtuController::class, 'index'])->name('wali.dashboard');
    Route::get('/wali/dashboard', [OrtuController::class, 'index'])->name('ortu.dashboard');

});
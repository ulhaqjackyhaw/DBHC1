<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DataKaryawanController;
use App\Http\Controllers\FormasiController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\VersionController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| Guest Routes (Tidak Perlu Login)
|--------------------------------------------------------------------------
*/

// Redirect root ke login jika belum login
Route::get('/', function () {
    return redirect()->route('login');
})->middleware('guest');

// Authentication routes
Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login'])->name('login.store');
});

// Logout route (bisa diakses semua user yang sudah login)
Route::post('logout', [LoginController::class, 'logout'])
    ->name('logout')
    ->middleware('auth');

/*
|--------------------------------------------------------------------------
| Authenticated Routes (Perlu Login)
|--------------------------------------------------------------------------
*/

// Route yang bisa diakses semua user yang sudah login
Route::middleware('auth')->group(function () {
    // Redirect root ke dashboard setelah login
    Route::get('/', function () {
        return redirect()->route('dashboard.index');
    });

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [ProfileController::class, 'updateProfile'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.update-password');

    // Dashboard dasar yang bisa diakses semua user
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

    // Analitik yang bisa diakses semua user
    Route::get('/analitikorganic', [EmployeeController::class, 'analitikOrganic'])->name('analitik.organic');
    Route::get('/analitikoutsourcing', [EmployeeController::class, 'analitikOutsourcing'])->name('analitik.outsourcing');

    // Route view & download yang bisa diakses semua user
    Route::get('/data-karyawan/template/download', [DataKaryawanController::class, 'downloadTemplate'])->name('karyawan.template.download');
    Route::get('/data-karyawan/export', [DataKaryawanController::class, 'export'])->name('karyawan.export');
    Route::get('/data-karyawan', [DataKaryawanController::class, 'index'])->name('karyawan.index');
    Route::get('/data-karyawan/{dataKaryawan}', [DataKaryawanController::class, 'show'])->name('karyawan.show');

    Route::get('/formasi/template/download', [FormasiController::class, 'downloadTemplate'])->name('formasi.template.download');
    Route::get('/formasi/export', [FormasiController::class, 'export'])->name('formasi.export');
    Route::get('/formasi', [FormasiController::class, 'index'])->name('formasi.index');
    Route::get('/formasi/{formasi}', [FormasiController::class, 'show'])->name('formasi.show');
});

/*
|--------------------------------------------------------------------------
| Admin Only Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'can:admin'])->group(function () {
    // Dashboard admin-only features
    Route::get('/dashboard/jabatan-lowong-detail', [DashboardController::class, 'getJabatanLowongDetail'])->name('dashboard.jabatan.detail');
    Route::get('/dashboard/jabatan-lowong-export', [DashboardController::class, 'exportJabatanLowongDetail'])->name('dashboard.jabatan.export');

    // Data Karyawan admin features
    Route::post('/data-karyawan/import-add', [DataKaryawanController::class, 'importAdd'])
        ->middleware('large.import')->name('karyawan.import.add');
    Route::post('/data-karyawan/import-replace', [DataKaryawanController::class, 'importReplace'])
        ->middleware('large.import')->name('karyawan.import.replace');
    Route::resource('/data-karyawan', DataKaryawanController::class)
        ->except(['index', 'show'])
        ->parameters(['data-karyawan' => 'dataKaryawan'])
        ->names('karyawan');

    // Formasi admin features
    Route::post('/formasi/import-add', [FormasiController::class, 'importAdd'])
        ->middleware('large.import')->name('formasi.import.add');
    Route::post('/formasi/import-replace', [FormasiController::class, 'importReplace'])
        ->middleware('large.import')->name('formasi.import.replace');
    Route::resource('/formasi', FormasiController::class)->except(['index', 'show']);

    // Version control (admin only)
    Route::prefix('versions')->name('versions.')->group(function () {
        Route::get('/', [VersionController::class, 'index'])->name('index');
        Route::post('/', [VersionController::class, 'store'])->name('store');
        Route::post('/{version}/restore', [VersionController::class, 'restore'])->name('restore');
        Route::get('/{version}/download', [VersionController::class, 'download'])->name('download');
        Route::delete('/{version}', [VersionController::class, 'destroy'])->name('destroy');
    });
});


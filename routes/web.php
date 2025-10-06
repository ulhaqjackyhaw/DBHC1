<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DataKaryawanController;
use App\Http\Controllers\FormasiController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\VersionController;
use App\Http\Controllers\Auth\LoginController; // <-- TAMBAHKAN INI

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- ROUTE UNTUK TAMU (BELUM LOGIN) ---
Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login'])->name('login.store');
});

// --- ROUTE UNTUK PENGGUNA YANG SUDAH LOGIN ---
Route::middleware(['auth'])->group(function () {

    // Logout
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');

    // Halaman utama setelah login
    Route::get('/', function () {
        return redirect()->route('dashboard.index');
    });

    // Route untuk halaman Dashboard Utama (Chart)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('/dashboard/jabatan-lowong-detail', [DashboardController::class, 'getJabatanLowongDetail'])->name('dashboard.jabatan.detail');
    Route::get('/dashboard/jabatan-lowong-export', [DashboardController::class, 'exportJabatanLowongDetail'])->name('dashboard.jabatan.export');

    // --- GRUP ROUTE KARYAWAN ---
    Route::get('/data-karyawan/template/download', [DataKaryawanController::class, 'downloadTemplate'])->name('karyawan.template.download');
    Route::get('/data-karyawan/export', [DataKaryawanController::class, 'export'])->name('karyawan.export');
    Route::post('/data-karyawan/import-add', [DataKaryawanController::class, 'importAdd'])
        ->middleware('large.import')->name('karyawan.import.add');
    Route::post('/data-karyawan/import-replace', [DataKaryawanController::class, 'importReplace'])
        ->middleware('large.import')->name('karyawan.import.replace');
    Route::resource('/data-karyawan', DataKaryawanController::class)
        ->parameters(['data-karyawan' => 'dataKaryawan'])
        ->names('karyawan');

    // --- GRUP ROUTE FORMASI ---
    Route::get('/formasi/template/download', [FormasiController::class, 'downloadTemplate'])->name('formasi.template.download');
    Route::get('/formasi/export', [FormasiController::class, 'export'])->name('formasi.export');
    Route::post('/formasi/import-add', [FormasiController::class, 'importAdd'])
        ->middleware('large.import')->name('formasi.import.add');
    Route::post('/formasi/import-replace', [FormasiController::class, 'importReplace'])
        ->middleware('large.import')->name('formasi.import.replace');
    Route::resource('/formasi', FormasiController::class);


    Route::get('/analitikorganic', [EmployeeController::class, 'analitikOrganic'])->name('analitik.organic');
    Route::get('/analitikoutsourcing', [EmployeeController::class, 'analitikOutsourcing'])->name('analitik.outsourcing');


    Route::prefix('versions')->name('versions.')->group(function () {
        Route::get('/', [VersionController::class, 'index'])->name('index');
        Route::post('/', [VersionController::class, 'store'])->name('store');
        Route::post('/{version}/restore', [VersionController::class, 'restore'])->name('restore');
        Route::get('/{version}/download', [VersionController::class, 'download'])->name('download');
        Route::delete('/{version}', [VersionController::class, 'destroy'])->name('destroy');
    });

}); // --- AKHIR DARI GRUP MIDDLEWARE ---


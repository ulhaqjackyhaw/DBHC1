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
Route::middleware('auth', )->group(function () {
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

    // Data Karyawan - Basic access routes (view only)
    Route::prefix('data-karyawan')->name('karyawan.')->group(function () {
        Route::get('/', [DataKaryawanController::class, 'index'])->name('index');
        Route::get('/create', [DataKaryawanController::class, 'create'])->name('create');
        Route::post('/', [DataKaryawanController::class, 'store'])->name('store');
        Route::get('/template/download', [DataKaryawanController::class, 'downloadTemplate'])->name('template.download');
        Route::get('/export', [DataKaryawanController::class, 'export'])->name('export');
        // Note: {dataKaryawan} route must be last to avoid conflicting with other routes
        Route::get('/{dataKaryawan}', [DataKaryawanController::class, 'show'])->name('show');
    });

    // Formasi routes moved to admin section
});

/*
|--------------------------------------------------------------------------
| Admin Only Routes
|--------------------------------------------------------------------------
*/

// Add debug logging for admin routes
Route::middleware(['auth', 'can:admin'])->group(function () {
    \Log::info('Accessing admin route', [
        'user' => auth()->check() ? auth()->user()->email : 'guest',
        'role' => auth()->check() ? auth()->user()->role : 'none',
        'url' => request()->url()
    ]);

    // Dashboard admin-only features
    Route::get('/dashboard/jabatan-lowong-detail', [DashboardController::class, 'getJabatanLowongDetail'])->name('dashboard.jabatan.detail');
    Route::get('/dashboard/jabatan-lowong-export', [DashboardController::class, 'exportJabatanLowongDetail'])->name('dashboard.jabatan.export');

    // Data Karyawan - Admin features
    Route::prefix('data-karyawan')->name('karyawan.')->group(function () {
        // Create operation - must come before the {dataKaryawan} route
        Route::get('/create', [DataKaryawanController::class, 'create'])->name('create');
        Route::post('/', [DataKaryawanController::class, 'store'])->name('store');


        // Import operations with large.import middleware
        Route::middleware('large.import')->group(function () {
            Route::post('import-add', [DataKaryawanController::class, 'importAdd'])->name('import.add');
            Route::post('import-replace', [DataKaryawanController::class, 'importReplace'])->name('import.replace');
        });

        // Resource operations that use {dataKaryawan} parameter must come last
        Route::get('{dataKaryawan}/edit', [DataKaryawanController::class, 'edit'])->name('edit');
        Route::put('{dataKaryawan}', [DataKaryawanController::class, 'update'])->name('update');
        Route::delete('{dataKaryawan}', [DataKaryawanController::class, 'destroy'])->name('destroy');
    });

    // Formasi - All routes (both admin and basic access)
    Route::prefix('formasi')->name('formasi.')->group(function () {
        // Basic access routes (view only)
        Route::get('/', [FormasiController::class, 'index'])->name('index');
        Route::get('/template/download', [FormasiController::class, 'downloadTemplate'])->name('template.download');
        Route::get('/export', [FormasiController::class, 'export'])->name('export');

        // Admin-only operations
        Route::get('/create', [FormasiController::class, 'create'])->name('create');
        Route::post('/', [FormasiController::class, 'store'])->name('store');
        Route::get('/{formasi}/edit', [FormasiController::class, 'edit'])->name('edit');
        Route::put('/{formasi}', [FormasiController::class, 'update'])->name('update');
        Route::delete('/{formasi}', [FormasiController::class, 'destroy'])->name('destroy');

        // Import operations with large.import middleware
        Route::middleware('large.import')->group(function () {
            Route::post('/import-add', [FormasiController::class, 'importAdd'])->name('import.add');
            Route::post('/import-replace', [FormasiController::class, 'importReplace'])->name('import.replace');
        });

        // Note: {formasi} show route must be last to avoid conflicting with other routes
        Route::get('/{formasi}', [FormasiController::class, 'show'])->name('show');
    });
    });


// Version control routes (accessible by all authenticated users)
Route::middleware('auth')->prefix('versions')->name('versions.')->group(function () {
    Route::get('/', [VersionController::class, 'index'])->name('index');
    Route::post('/', [VersionController::class, 'store'])->name('store');
    Route::post('/{version}/restore', [VersionController::class, 'restore'])->name('restore');
    Route::get('/{version}/download', [VersionController::class, 'download'])->name('download');
    Route::delete('/{version}', [VersionController::class, 'destroy'])->name('destroy');
});


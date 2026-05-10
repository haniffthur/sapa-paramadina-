<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\UserAssetController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\RoomController;
use App\Http\Controllers\Admin\AssetController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ProdiController;
use App\Http\Controllers\Admin\PeminjamanController as AdminPeminjamanController;
use App\Http\Controllers\Petugas\DashboardController as PetugasDashboardController;
use App\Http\Controllers\Petugas\PeminjamanController as PetugasPeminjamanController;
use App\Http\Controllers\Admin\AssetReportController;
use App\Http\Controllers\Mahasiswa\ProfileController;

/*
|--------------------------------------------------------------------------
| Web Routes - SAPA PARAMADINA (Final Integrated Version)
|--------------------------------------------------------------------------
*/

// --- PUBLIC & GUEST ---
Route::get('/', function () {
    return redirect()->route('login');
});

Route::controller(GoogleController::class)->group(function () {
    Route::get('/login', 'index')->name('login')->middleware('guest');
    Route::get('auth/google', 'redirectToGoogle')->name('google.login');
    Route::get('auth/google/callback', 'handleGoogleCallback');
    Route::post('/logout', 'logout')->name('logout');
});

// --- AUTHENTICATED USERS ---
Route::middleware(['auth'])->group(function () {
    // Pengisian Profil Awal (NIM & Prodi)
    Route::get('/complete-profile', [ProfileController::class, 'showCompleteForm'])->name('profile.complete');
    Route::post('/complete-profile', [ProfileController::class, 'updateProfile'])->name('profile.update');
});

/*
|--------------------------------------------------------------------------
| JALUR MAHASISWA
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'profile.complete'])->group(function () {
    
    Route::get('/dashboard', [UserAssetController::class, 'index'])->name('dashboard');
    Route::get('/assets', [UserAssetController::class, 'showAssets'])->name('peminjaman.assets');
    Route::get('/history', [UserAssetController::class, 'history'])->name('peminjaman.history');
    Route::get('/penalties', [UserAssetController::class, 'penalties'])->name('peminjaman.penalties');
    
    // Transaksi & Peminjaman
    Route::get('/scan', [UserAssetController::class, 'scanArea'])->name('scan.area');
    Route::get('/scan/room/{token}', [UserAssetController::class, 'scanRoom'])->name('scan.room');
    Route::get('/pinjam/confirm/{asset_id}', [UserAssetController::class, 'confirmPinjam'])->name('peminjaman.confirm');
    Route::post('/pinjam/store', [UserAssetController::class, 'storePeminjaman'])->name('peminjaman.store');
    Route::post('/pinjam/selesai/{id}', [UserAssetController::class, 'selesai'])->name('peminjaman.selesai');
    Route::post('/pinjam/store-multi', [UserAssetController::class, 'storeMultiPeminjaman'])->name('peminjaman.storeMulti');
    Route::get('/assets/{id}/detail', [UserAssetController::class, 'assetDetail'])->name('peminjaman.asset.detail');
    
    // Pembayaran Denda (Upload Bukti)
    Route::post('/penalties/{id}/pay', [UserAssetController::class, 'payPenalty'])->name('peminjaman.penalties.pay');

    // Profile & Logout
    Route::get('/profile', [UserAssetController::class, 'profile'])->name('profile');
    Route::post('/logout', [GoogleController::class, 'logout'])->name('logout');
});

/*
|--------------------------------------------------------------------------
| JALUR ADMIN
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'is_admin'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard & Reports
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
    Route::get('/reports', [AdminController::class, 'report'])->name('report');

    // Master Data Resources (CRUD)
    Route::resource('rooms', RoomController::class);
    Route::resource('assets', AssetController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('prodis', ProdiController::class);

    // Approval Peminjaman
    Route::controller(AdminPeminjamanController::class)->group(function () {
        Route::get('/peminjaman', 'index')->name('peminjaman.index'); 
        Route::post('/peminjaman/{id}/approve', 'approve')->name('approve');
        Route::post('/peminjaman/{id}/reject', 'reject')->name('reject');
    });

    // Manajemen Denda & Penalti
    Route::controller(AdminController::class)->group(function () {
        Route::get('/penalties', 'penalties')->name('penalties.index');
        Route::post('/penalties/store-manual', 'storeManualPenalty')->name('penalties.store-manual');
        Route::post('/penalties/{id}/paid', 'paid')->name('penalties.paid'); // Gunakan 'paid' sesuai controller terbaru lo
    });
    
    // Settings (Denda per jam)
    Route::get('/settings', [AdminController::class, 'settings'])->name('settings.index');
    Route::post('/settings/update', [AdminController::class, 'updateSettings'])->name('settings.update');

    // User Management
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::post('/users/{id}/update-role', [UserController::class, 'updateRole'])->name('users.update-role');

    // Review Laporan Kerusakan dari Petugas (OB)
    Route::controller(AssetReportController::class)->group(function () {
        Route::get('/reportasset', 'index')->name('asset-reports.index');
        Route::post('/reportasset/{id}/penalty', 'approveAndPenalty')->name('asset-reports.penalty');
        Route::patch('/reportasset/{id}/status', 'updateStatus')->name('asset-reports.update');
    });
});

/*
|--------------------------------------------------------------------------
| JALUR PETUGAS (OB)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'isPetugas'])->prefix('petugas')->name('petugas.')->group(function () {
    
    // Dashboard & Monitoring
    Route::get('/dashboard', [PetugasDashboardController::class, 'index'])->name('dashboard');
    Route::get('/peminjaman', [PetugasPeminjamanController::class, 'index'])->name('peminjaman.index');
    
    // Pelaporan Kerusakan
    Route::post('/lapor-kerusakan', [PetugasDashboardController::class, 'storeReport'])->name('report.store');
    Route::get('/riwayat-laporan', [PetugasDashboardController::class, 'historyReport'])->name('reports.index');
});
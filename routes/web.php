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



/*
| Web Routes - SAPA PARAMADINA (Final Sync Version)
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


// --- JALUR MAHASISWA (Authenticated) ---
Route::middleware(['auth'])->group(function () {
    
    // Dashboard Utama
    Route::get('/dashboard', [UserAssetController::class, 'index'])->name('dashboard');

    // Menu Aksi Cepat (Grid)
    Route::get('/assets', [UserAssetController::class, 'showAssets'])->name('peminjaman.assets');
    Route::get('/history', [UserAssetController::class, 'history'])->name('peminjaman.history');
    Route::get('/penalties', [UserAssetController::class, 'penalties'])->name('peminjaman.penalties');
    Route::get('/profile', [UserAssetController::class, 'profile'])->name('profile');

    // Fitur Scan QR
    Route::get('/scan', [UserAssetController::class, 'scanArea'])->name('scan.area');
    Route::get('/scan/room/{token}', [UserAssetController::class, 'scanRoom'])->name('scan.room');

    // Proses Peminjaman
    Route::get('/pinjam/confirm/{asset_id}', [UserAssetController::class, 'confirmPinjam'])->name('peminjaman.confirm');
    Route::post('/pinjam/store', [UserAssetController::class, 'storePeminjaman'])->name('peminjaman.store');
    Route::post('/pinjam/selesai/{id}', [UserAssetController::class, 'selesai'])->name('peminjaman.selesai');

    Route::post('/pinjam/store-multi', [UserAssetController::class, 'storeMultiPeminjaman'])->name('peminjaman.storeMulti');

    Route::get('/assets/{id}/detail', [UserAssetController::class, 'assetDetail'])->name('peminjaman.asset.detail');

    // Logout
    Route::post('/logout', [GoogleController::class, 'logout'])->name('logout');
});


// --- JALUR ADMIN (Authenticated & is_admin) ---
Route::middleware(['auth', 'is_admin'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard Utama Admin
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');

    // CRUD Resources
    Route::resource('rooms', RoomController::class);
    Route::resource('assets', AssetController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('prodis', ProdiController::class);

    // Approval Peminjaman (Admin Side)
    Route::controller(AdminPeminjamanController::class)->group(function () {
        Route::get('/peminjaman', 'index')->name('peminjaman.index'); 
        Route::post('/peminjaman/{id}/approve', 'approve')->name('approve');
        Route::post('/peminjaman/{id}/reject', 'reject')->name('reject');
    });

    // Fitur Denda (Penalty)
    Route::get('/penalties', [AdminController::class, 'penalties'])->name('penalties.index');
    Route::post('/penalties/{id}/paid', [AdminController::class, 'markAsPaid'])->name('penalties.paid');

    // Laporan & Log
    Route::get('/reports', [AdminController::class, 'report'])->name('report');

    // User Management
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::post('/users/{id}/update-role', [UserController::class, 'updateRole'])->name('users.update-role');

    // Manajemen Laporan Kerusakan dari Petugas
// Halaman list semua laporan dari petugas
    Route::get('/reportasset', [AssetReportController::class, 'index'])->name('asset-reports.index');
    
    // Proses update status (Menunggu -> Diproses -> Selesai)
    Route::patch('/reportasset/{id}/status', [AssetReportController::class, 'updateStatus'])->name('asset-reports.update');
});

Route::middleware(['auth', 'isPetugas'])->prefix('petugas')->name('petugas.')->group(function () {
    
    // Dashboard Utama Petugas
    Route::get('/dashboard', [PetugasDashboardController::class, 'index'])->name('dashboard');
    
    // Fitur Pantau Pinjaman (Hanya View & List)
    Route::get('/peminjaman', [PetugasPeminjamanController::class, 'index'])->name('peminjaman.index');
    
    // Fitur Lapor Kerusakan Aset (Post dari Modal)
    Route::post('/lapor-kerusakan', [PetugasDashboardController::class, 'storeReport'])->name('report.store');

    Route::get('/riwayat-laporan', [PetugasDashboardController::class, 'historyReport'])->name('reports.index');

    // Nanti bisa tambah route riwayat laporan di sini
    // Route::get('/riwayat-laporan', [ReportController::class, 'index'])->name('report.index');
});
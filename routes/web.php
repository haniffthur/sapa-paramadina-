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


/*
|--------------------------------------------------------------------------
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
});
<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

/**
 * ─── Client Authentication Routes ──────────────────────────────────────────────
 */
Route::middleware('guest')->group(function () {
    // Login
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/dang-nhap', fn() => redirect()->route('login'));

    // Register
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/dang-ky', fn() => redirect()->route('register'))->name('auth.register');
});

Route::middleware('auth')->group(function () {
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('/dang-xuat', [AuthController::class, 'logout'])->name('auth.logout');
});

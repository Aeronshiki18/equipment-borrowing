<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\OtpPasswordResetController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    
    // --- REGISTRATION ---
    Route::get('register', [RegisteredUserController::class, 'create'])
                ->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);

    // --- LOGIN ---
    Route::get('login', [AuthenticatedSessionController::class, 'create'])
                ->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    // --- CUSTOM OTP PASSWORD RESET ---
    Route::get('forgot-password', [OtpPasswordResetController::class, 'showRequestForm'])->name('password.request');
    Route::post('forgot-password', [OtpPasswordResetController::class, 'sendOtp'])->name('password.email.otp');
    
    Route::get('verify-otp', [OtpPasswordResetController::class, 'showVerifyForm'])->name('password.verify.form');
    Route::post('verify-otp', [OtpPasswordResetController::class, 'verifyOtp'])->name('password.verify.otp');
    
    Route::get('reset-password-otp', [OtpPasswordResetController::class, 'showResetForm'])->name('password.reset.form');
    Route::post('reset-password-otp', [OtpPasswordResetController::class, 'resetPassword'])->name('password.update.otp');
});

Route::middleware('auth')->group(function () {
    // Standard Logout
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
                ->name('logout');
});
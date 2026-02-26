<?php

use App\Http\Controllers\Auth\Customer\AuthenticatedSessionController;
use App\Http\Controllers\Auth\Customer\ConfirmablePasswordController;
use App\Http\Controllers\Auth\Customer\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\Customer\EmailVerificationPromptController;
use App\Http\Controllers\Auth\Customer\NewPasswordController;
use App\Http\Controllers\Auth\Customer\PasswordController;
use App\Http\Controllers\Auth\Customer\PasswordResetLinkController;
use App\Http\Controllers\Auth\Customer\RegisteredUserController;
use App\Http\Controllers\Auth\Customer\VerifyEmailController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {

    Route::get('/', [AuthenticatedSessionController::class, 'create'])->name('customer.login');

    Route::get('customer-register', [RegisteredUserController::class, 'create'])
        ->name('customer.register');

    Route::post('customer-register', [RegisteredUserController::class, 'store']);

    Route::get('customer-login', [AuthenticatedSessionController::class, 'create'])
        ->name('customer.login');

    Route::post('customer-login', [AuthenticatedSessionController::class, 'store'])
        ->name('customer.login.store');

    Route::get('customer-forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('customer.password.request');

    Route::post('customer-forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('customer.password.email');

    Route::get('customer-reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('customer.password.reset');

    Route::post('customer-reset-password', [NewPasswordController::class, 'store'])
        ->name('customer.password.store');
});

Route::middleware('auth')->group(function () {
    Route::get('customer-verify-email', EmailVerificationPromptController::class)
        ->name('customer.verification.notice');

    Route::get('customer-verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('customer.verification.verify');

    Route::post('customer-email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('customer.verification.send');

    Route::get('customer-confirm-password', [ConfirmablePasswordController::class, 'show'])
        ->name('customer.password.confirm');

    Route::post('customer-confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::put('customer-password', [PasswordController::class, 'update'])->name('customer.password.update');

    Route::post('customer-logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('customer.logout');
});

<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PortalApplicationController;
use App\Http\Controllers\PortalController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SsoGuideController;
use App\Http\Controllers\SsoLaunchController;
use App\Http\Controllers\UserManagementController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/portal');

Route::get('/panduan/sso', function () {
    return response()->file(public_path('docs/panduan-implementasi-sso-sipadu.html'));
})->name('docs.sso.html');

Route::get('/panduan/sso/pdf', function () {
    return response()->file(public_path('docs/panduan-implementasi-sso-sipadu.pdf'));
})->name('docs.sso.pdf');

Route::get('/panduan/sso-ringkas/pdf', function () {
    return response()->file(public_path('docs/panduan-implementasi-sso-sipadu-ringkas.pdf'));
})->name('docs.sso.concise.pdf');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'create'])->name('login');
    Route::post('/login', [AuthController::class, 'store'])->name('login.store');
});

Route::middleware('auth')->group(function () {
    Route::get('/portal', [PortalController::class, 'index'])->name('portal.index');
    Route::get('/portal/search', [PortalController::class, 'search'])->name('portal.search');
    Route::get('/launch/{application:slug}', SsoLaunchController::class)->name('portal.launch');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/logout', [AuthController::class, 'destroy'])->name('logout');
});

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/panduan/sso-ringkas', [SsoGuideController::class, 'concise'])->name('docs.sso.concise.html');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::resource('portal-applications', PortalApplicationController::class)->except('show');
    Route::resource('users', UserManagementController::class)->except('show', 'destroy');
});

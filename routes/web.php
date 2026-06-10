<?php

use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\ForcePasswordChangeController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PortalApplicationController;
use App\Http\Controllers\PortalController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SsoGuideController;
use App\Http\Controllers\SsoLogController;
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

// Guest routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'create'])->name('login');
    Route::post('/login', [AuthController::class, 'store'])->name('login.store');

    // Forgot password
    Route::get('/forgot-password', [ForgotPasswordController::class, 'create'])->name('forgot-password');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'store'])->name('forgot-password.store');
    Route::get('/forgot-password/sent', [ForgotPasswordController::class, 'sent'])->name('forgot-password.sent');
    Route::get('/reset-password/{token}', [ForgotPasswordController::class, 'resetForm'])->name('password.reset');
    Route::post('/reset-password', [ForgotPasswordController::class, 'reset'])->name('password.reset.store');
});

// Authenticated routes (with password expiry check)
Route::middleware(['auth', 'password.check'])->group(function () {
    Route::get('/portal', [PortalController::class, 'index'])->name('portal.index');
    Route::get('/portal/search', [PortalController::class, 'search'])->name('portal.search');
    Route::get('/launch/{application:slug}', SsoLaunchController::class)->name('portal.launch');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/logout', [AuthController::class, 'destroy'])->name('logout');

    // Notifications (all authenticated users)
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllRead'])->name('notifications.mark-all-read');
});

// Force change password (authenticated but bypass password.check middleware)
Route::middleware('auth')->group(function () {
    Route::get('/force-change-password', [ForcePasswordChangeController::class, 'create'])->name('force-change-password');
    Route::post('/force-change-password', [ForcePasswordChangeController::class, 'store'])->name('force-change-password.store');
});

// Admin routes
Route::middleware(['auth', 'admin', 'password.check'])->group(function () {
    Route::get('/panduan/sso-ringkas', [SsoGuideController::class, 'concise'])->name('docs.sso.concise.html');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('/sso-logs', [SsoLogController::class, 'index'])->name('sso-logs.index');
    Route::get('/sso-logs/{ssoLog}', [SsoLogController::class, 'show'])->name('sso-logs.show');

    // User management
    Route::get('/users/export', [UserManagementController::class, 'export'])->name('users.export');
    Route::post('/users/{user}/reset-password', [UserManagementController::class, 'resetPassword'])->name('users.reset-password');
    Route::post('/users/{user}/unlock', [UserManagementController::class, 'unlock'])->name('users.unlock');
    Route::resource('users', UserManagementController::class)->except('show', 'destroy');

    // Portal applications
    Route::resource('portal-applications', PortalApplicationController::class)->except('show');

    // Announcements
    Route::resource('announcements', AnnouncementController::class)->except('show');

    // Audit logs
    Route::get('/audit-logs', [AuditLogController::class, 'index'])->name('audit-logs.index');
});

// API routes for notifications (future-ready - enable when Sanctum is installed)
// Route::middleware('auth:sanctum')->group(function () {
//     Route::post('/api/notifications', [NotificationController::class, 'apiStore'])->name('api.notifications.store');
// });

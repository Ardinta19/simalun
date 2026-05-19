<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\CustomerAddressController;
use App\Http\Controllers\FinanceController;

/*
|--------------------------------------------------------------------------
| SIMALUN – Azka Laundry Web Routes
| Struktur: Guest → Auth → Customer → Admin → Driver
|--------------------------------------------------------------------------
*/

/* ═══════════════════════════════════════════════════
   GUEST ROUTES (tidak perlu login)
═══════════════════════════════════════════════════ */
Route::middleware('guest')->group(function () {

    // Splash / Welcome
    Route::get('/', fn() => view('welcome'))->name('welcome');

    // Login
    Route::get('/login', fn() => view('auth.login'))->name('login');
    Route::post('/login', [LoginController::class, 'authenticate'])->name('login.post');

    // Register
    Route::get('/register', [RegisterController::class, 'showForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'store'])->name('register.post');

    // Forgot Password
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendLink'])->name('password.email');

    // Reset Password
    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showForm'])->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class, 'update'])->name('password.update.reset');
});

/* ═══════════════════════════════════════════════════
   AUTH ROUTES (perlu login, semua role)
═══════════════════════════════════════════════════ */
Route::middleware('auth')->group(function () {

    // Logout
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Dashboard router – redirect ke dashboard masing-masing role
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Email Verification
    Route::get('/verify-email', fn() => view('auth.verify-email'))->name('verification.notice');
    Route::get('/verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');
    Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    // Confirm Password
    Route::get('/confirm-password', [ConfirmablePasswordController::class, 'show'])->name('password.confirm');
    Route::post('/confirm-password', [ConfirmablePasswordController::class, 'store']);

    // Password Update (dari profile)
    Route::put('/password', [PasswordController::class, 'update'])->name('password.update');

    // Profile (semua role)
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    /* ─────────────────────────────────────────────────
       ORDER – Shared (customer membuat, admin mengelola)
    ───────────────────────────────────────────────── */
    // Buat pesanan – Customer only
    Route::get('/order/create', [OrderController::class, 'create'])
        ->middleware('role:customer')
        ->name('order.create');
    Route::post('/order/store', [OrderController::class, 'store'])
        ->middleware('role:customer')
        ->name('order.store');
    Route::get('/order/{orderCode}', [OrderController::class, 'show'])
        ->name('order.show');

    // Struk – bisa diakses admin & customer
    Route::get('/orders/{order}/receipt', [OrderController::class, 'receipt'])
        ->name('admin.orders.receipt');

    /* ─────────────────────────────────────────────────
       CUSTOMER ROUTES
    ───────────────────────────────────────────────── */
    Route::middleware('role:customer')->prefix('customer')->name('customer.')->group(function () {

        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'customer'])->name('dashboard');

        // Profil
        Route::get('/profile', [ProfileController::class, 'customerShow'])->name('profile');

        // Pesanan
        Route::get('/orders', [OrderController::class, 'customerIndex'])->name('orders');
        Route::get('/orders/{order}', [OrderController::class, 'customerDetail'])->name('order.detail');
        Route::get('/tracking', [OrderController::class, 'tracking'])->name('tracking');

        // Notifikasi
        Route::get('/notifications', [NotificationController::class, 'customerIndex'])->name('notifications');
        Route::post('/notifications/read-all', [NotificationController::class, 'readAll'])->name('notifications.read-all');
        Route::patch('/notifications/{id}/read', [NotificationController::class, 'markRead'])->name('notifications.read');
        Route::get('/notifications/{id}/click', [NotificationController::class, 'click'])->name('notifications.click');

        // Alamat
        Route::get('/addresses', [CustomerAddressController::class, 'index'])->name('addresses.index');
        Route::get('/addresses/create', [CustomerAddressController::class, 'create'])->name('addresses.create');
        Route::post('/addresses', [CustomerAddressController::class, 'store'])->name('addresses.store');
        Route::get('/addresses/{address}/edit', [CustomerAddressController::class, 'edit'])->name('addresses.edit');
        Route::put('/addresses/{address}', [CustomerAddressController::class, 'update'])->name('addresses.update');
        Route::delete('/addresses/{address}', [CustomerAddressController::class, 'destroy'])->name('addresses.destroy');
        Route::patch('/addresses/{address}/primary', [CustomerAddressController::class, 'setPrimary'])->name('addresses.set-primary');

        // Bantuan
        Route::get('/help', fn() => view('roles.customer.help'))->name('help');
    });

    /* ─────────────────────────────────────────────────
       ADMIN ROUTES
    ───────────────────────────────────────────────── */
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {

        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'admin'])->name('dashboard');
        // Alias untuk backward compat
        Route::get('/dashboard-alias', [DashboardController::class, 'admin'])->name('dashboard.admin.alias');

        // Pesanan – Manajemen
        Route::get('/orders', [OrderController::class, 'adminIndex'])->name('orders');
        Route::post('/orders/{order}/assign-driver', [OrderController::class, 'assignDriver'])->name('orders.assign-driver');
        Route::patch('/orders/{order}/update-status', [OrderController::class, 'updateStatus'])->name('orders.update-status');

        // Walk-in
        Route::get('/walkin', [OrderController::class, 'walkinForm'])->name('walkin.form');
        Route::post('/walkin', [OrderController::class, 'walkinStore'])->name('orders.walk-in.store');

        // Keuangan
        Route::get('/finance', [FinanceController::class, 'index'])->name('finance.index');
        Route::post('/finance', [FinanceController::class, 'store'])->name('finance.store');
        Route::get('/finance/export', [FinanceController::class, 'export'])->name('finance.export');

        // Notifikasi
        Route::get('/notifications', [NotificationController::class, 'adminIndex'])->name('notifications');

        // Profil admin
        Route::get('/profile', [ProfileController::class, 'adminShow'])->name('profile');
    });

    /* ─────────────────────────────────────────────────
       DRIVER ROUTES
    ───────────────────────────────────────────────── */
    Route::middleware('role:driver')->prefix('driver')->name('driver.')->group(function () {

        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'driver'])->name('dashboard');

        // Tugas / Pesanan
        Route::get('/orders', [OrderController::class, 'driverIndex'])->name('orders');
        Route::get('/orders/{order}', [OrderController::class, 'driverDetail'])->name('orders.show');
        Route::post('/orders/{order}/action', [OrderController::class, 'driverAction'])->name('orders.action');

        // Tracking
        Route::get('/tracking', [OrderController::class, 'driverTracking'])->name('tracking');

        // Notifikasi
        Route::get('/notifications', [NotificationController::class, 'driverIndex'])->name('notifications');

        // Bantuan
        Route::get('/help', fn() => view('roles.driver.help'))->name('help');
    });
});

/* ═══════════════════════════════════════════════════
   NAMED ROUTE ALIASES (backward compatibility)
   Beberapa view lama pakai route name lama ini
═══════════════════════════════════════════════════ */
Route::middleware('auth')->group(function () {
    // Dashboard routing berdasarkan role
    Route::get('/dashboard/admin',  [DashboardController::class, 'admin'])->name('dashboard.admin');
    Route::get('/dashboard/driver', [DashboardController::class, 'driver'])->name('dashboard.driver');
    Route::get('/dashboard/customer', [DashboardController::class, 'customer'])->name('dashboard.customer');
});
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

Route::middleware('guest')->group(function () {
    Route::get('/', fn() => view('welcome'))->name('welcome');

    Route::get('/login', fn() => view('auth.login'))->name('login');
    Route::post('/login', [LoginController::class, 'authenticate'])
        ->middleware('throttle:5,1')
        ->name('login.post');

    Route::get('/register', [RegisterController::class, 'showForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'store'])
        ->middleware('throttle:3,1')
        ->name('register.post');

    Route::get('/forgot-password', [ForgotPasswordController::class, 'showForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendLink'])
        ->middleware('throttle:3,1')
        ->name('password.email');

    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showForm'])->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class, 'update'])->name('password.update.reset');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Email verification (opsional — hanya untuk user yang mengisi email)
    Route::get('/verify-email', fn() => view('auth.verify-email'))->name('verification.notice');
    Route::get('/verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');
    Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    Route::get('/confirm-password', [ConfirmablePasswordController::class, 'show'])->name('password.confirm');
    Route::post('/confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::put('/password', [PasswordController::class, 'update'])->name('password.update');

    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::delete('/profile/avatar', [ProfileController::class, 'deleteAvatar'])->name('profile.avatar.delete');
    Route::post('/profile/primary-address', [ProfileController::class, 'savePrimaryAddress'])
        ->middleware('role:customer')
        ->name('profile.primary-address');

    Route::get('/order/create', [OrderController::class, 'create'])
        ->middleware(['role:customer'])
        ->name('order.create');
    Route::post('/order/store', [OrderController::class, 'store'])
        ->middleware(['role:customer', 'throttle:6,1'])
        ->name('order.store');
    Route::get('/order/{orderCode}', [OrderController::class, 'show'])
        ->name('order.show');

    Route::get('/orders/{order}/receipt', [OrderController::class, 'receipt'])
        ->name('admin.orders.receipt');
    Route::get('/orders/{order}/receipt/pdf', [OrderController::class, 'receiptPdf'])
        ->name('orders.receipt.pdf');

    Route::middleware('role:customer')->prefix('customer')->name('customer.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'customer'])->name('dashboard');

        Route::get('/profile', [ProfileController::class, 'customerShow'])->name('profile');

        Route::get('/orders', [OrderController::class, 'customerIndex'])->name('orders');
        Route::get('/orders/{order}', [OrderController::class, 'customerDetail'])->name('order.detail');
        Route::post('/orders/{order}/cancel', [OrderController::class, 'customerCancel'])->name('order.cancel');
        Route::get('/tracking', [OrderController::class, 'tracking'])->name('tracking');

        Route::get('/notifications', [NotificationController::class, 'customerIndex'])->name('notifications');
        Route::post('/notifications/read-all', [NotificationController::class, 'readAll'])->name('notifications.read-all');
        Route::patch('/notifications/{id}/read', [NotificationController::class, 'markRead'])->name('notifications.read');
        Route::get('/notifications/{id}/open', [NotificationController::class, 'markAndRedirect'])->name('notifications.open');

        Route::get('/addresses', [CustomerAddressController::class, 'index'])->name('addresses.index');
        Route::get('/addresses/create', [CustomerAddressController::class, 'create'])->name('addresses.create');
        Route::post('/addresses', [CustomerAddressController::class, 'store'])->name('addresses.store');
        Route::get('/addresses/{address}/edit', [CustomerAddressController::class, 'edit'])->name('addresses.edit');
        Route::put('/addresses/{address}', [CustomerAddressController::class, 'update'])->name('addresses.update');
        Route::delete('/addresses/{address}', [CustomerAddressController::class, 'destroy'])->name('addresses.destroy');
        Route::patch('/addresses/{address}/primary', [CustomerAddressController::class, 'setPrimary'])->name('addresses.set-primary');

        Route::get('/report', [\App\Http\Controllers\ReportController::class, 'create'])->name('report');
        Route::post('/report', [\App\Http\Controllers\ReportController::class, 'store'])->name('report.store');

        Route::get('/help', fn() => view('roles.customer.help'))->name('help');
    });

    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'admin'])->name('dashboard');

        Route::get('/orders', [OrderController::class, 'adminIndex'])->name('orders');
        Route::post('/orders/{order}/assign-driver', [OrderController::class, 'assignDriver'])->name('orders.assign-driver');
        Route::patch('/orders/{order}/update-status', [OrderController::class, 'updateStatus'])->name('orders.update-status');

        Route::get('/walkin', [OrderController::class, 'walkinForm'])->name('walkin.form');
        Route::post('/walkin', [OrderController::class, 'walkinStore'])->name('orders.walk-in.store');

        Route::get('/finance', [FinanceController::class, 'index'])->name('finance.index');
        Route::post('/finance', [FinanceController::class, 'store'])->name('finance.store');
        Route::get('/finance/export', [FinanceController::class, 'export'])->name('finance.export');
        Route::get('/finance/export-pdf', [FinanceController::class, 'exportPdf'])->name('finance.export-pdf');

        Route::get('/reports', [\App\Http\Controllers\ReportController::class, 'adminIndex'])->name('reports');
        Route::patch('/reports/{report}/status', [\App\Http\Controllers\ReportController::class, 'updateStatus'])->name('reports.update-status');

        Route::get('/notifications', [NotificationController::class, 'adminIndex'])->name('notifications');

        Route::get('/profile', [ProfileController::class, 'adminShow'])->name('profile');

        Route::get('/help', fn() => view('roles.admin.help'))->name('help');
    });

    Route::middleware('role:driver')->prefix('driver')->name('driver.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'driver'])->name('dashboard');

        Route::get('/orders', [OrderController::class, 'driverIndex'])->name('orders');
        Route::get('/orders/{order}', [OrderController::class, 'driverDetail'])->name('orders.show');
        Route::post('/orders/{order}/action', [OrderController::class, 'driverAction'])->name('orders.action');

        Route::get('/tracking', [OrderController::class, 'driverTracking'])->name('tracking');

        Route::get('/notifications', [NotificationController::class, 'driverIndex'])->name('notifications');

        Route::get('/help', fn() => view('roles.driver.help'))->name('help');

        Route::get('/report', [\App\Http\Controllers\ReportController::class, 'create'])->name('report');
        Route::post('/report', [\App\Http\Controllers\ReportController::class, 'store'])->name('report.store');

        Route::get('/profile', fn() => view('roles.driver.profile'))->name('profile');
    });

    Route::get('/dashboard/admin',  [DashboardController::class, 'admin'])->middleware('role:admin')->name('dashboard.admin');
    Route::get('/dashboard/driver', [DashboardController::class, 'driver'])->middleware('role:driver')->name('dashboard.driver');
    Route::get('/dashboard/customer', [DashboardController::class, 'customer'])->middleware('role:customer')->name('dashboard.customer');
});

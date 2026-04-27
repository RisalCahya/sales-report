<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminSalesController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\NoCacheHeaders;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', DashboardController::class)
    ->middleware(['auth', 'verified', NoCacheHeaders::class])
    ->name('dashboard');

Route::middleware(['auth', NoCacheHeaders::class])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Report routes
    Route::resource('reports', ReportController::class);
    Route::post('/reports/{report}/details', [ReportController::class, 'addDetail'])->name('reports.addDetail');
    Route::get('/reports-export', [ReportController::class, 'export'])->name('reports.export');

    // Admin sales management
    Route::get('/admin/sales', [AdminSalesController::class, 'index'])->name('admin.sales.index');
    Route::post('/admin/sales', [AdminSalesController::class, 'store'])->name('admin.sales.store');
    Route::patch('/admin/sales/{sales}/status', [AdminSalesController::class, 'toggleStatus'])->name('admin.sales.toggle-status');
    Route::post('/admin/sales/{sales}/reset-password', [AdminSalesController::class, 'resetPassword'])->name('admin.sales.reset-password');
});

require __DIR__ . '/auth.php';

<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminSalesController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use App\Http\Middleware\NoCacheHeaders;

// Serve public storage files directly via Laravel to support shared hosting
// where Apache FollowSymLinks is disabled (e.g. Niagahoster).
Route::get('/storage/{path}', function (string $path) {
    // Prevent path traversal attacks
    $path = ltrim($path, '/');
    if (str_contains($path, '..')) {
        abort(404);
    }

    if (!Storage::disk('public')->exists($path)) {
        abort(404);
    }

    return response()->file(
        Storage::disk('public')->path($path),
        ['Cache-Control' => 'public, max-age=86400']
    );
})->where('path', '.*');

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified', NoCacheHeaders::class])->name('dashboard');

Route::middleware(['auth', NoCacheHeaders::class])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Report routes
    Route::resource('reports', ReportController::class);
    Route::post('/reports/{report}/details', [ReportController::class, 'addDetail'])->name('reports.addDetail');

    // Admin sales management
    Route::get('/admin/sales', [AdminSalesController::class, 'index'])->name('admin.sales.index');
    Route::post('/admin/sales', [AdminSalesController::class, 'store'])->name('admin.sales.store');
    Route::patch('/admin/sales/{sales}/status', [AdminSalesController::class, 'toggleStatus'])->name('admin.sales.toggle-status');
    Route::post('/admin/sales/{sales}/reset-password', [AdminSalesController::class, 'resetPassword'])->name('admin.sales.reset-password');
});

require __DIR__ . '/auth.php';

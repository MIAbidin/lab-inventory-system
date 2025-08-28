<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect('/login');
});

// Authentication Routes
Auth::routes(['register' => false, 'reset' => false, 'verify' => false]);

// Protected Routes
Route::middleware(['auth'])->group(function () {
    
    // Dashboard - Hapus duplikasi /home route
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/home', [DashboardController::class, 'index'])->name('home');
    
    // Inventory Management
    Route::resource('inventory', InventoryController::class);
    
    // Category Management
    Route::resource('categories', CategoryController::class)->except(['show']);
    
    // Reports
    Route::prefix('reports')->name('reports.')->group(function () {
        // Main reports page with filtering
        Route::get('/', [ReportController::class, 'index'])->name('index');
        
        // Condition reports
        Route::get('/condition', [ReportController::class, 'conditionReport'])->name('condition');
        
        // Export routes untuk laporan utama
        Route::get('/export-pdf', [ReportController::class, 'exportPdf'])->name('export.pdf');
        Route::get('/export-excel', [ReportController::class, 'exportExcel'])->name('export.excel');

        // Financial reports
        // Route::get('/financial', [ReportController::class, 'financialReport'])->name('financial');
        // Route::get('/financial/export-pdf', [ReportController::class, 'exportFinancialPdf'])->name('financial.export.pdf');
        // Route::get('/financial/export-excel', [ReportController::class, 'exportFinancialExcel'])->name('financial.export.excel');
    });
    
    // Profile Management
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
});

// Hapus duplikasi Auth::routes() dan route /home yang redundant
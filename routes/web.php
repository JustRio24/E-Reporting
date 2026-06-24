<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FacilityCategoryController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\FacilityController;
use App\Http\Controllers\DamageCategoryController;
use App\Http\Controllers\DamageReportController;
use App\Http\Controllers\WorkOrderController;
use App\Http\Controllers\RepairProgressController;
use App\Http\Controllers\GisController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Redirect guest root to login
Route::get('/', function () {
    return redirect()->route('login');
});

// Authenticated and active routes
Route::middleware(['auth', 'active'])->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Notifications
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');

    // Profile Management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // GIS Monitoring
    Route::get('/gis-monitoring', [GisController::class, 'index'])->name('gis.index');
    Route::get('/gis-monitoring/data', [GisController::class, 'mapData'])->name('gis.data');
    Route::get('/gis-monitoring/facilities', [GisController::class, 'facilitiesData'])->name('gis.facilities');

    // Master Data & Management (Restricted by roles)
    
    // Admin Only
    Route::middleware('role:admin')->group(function () {
        Route::resource('users', UserController::class);
        Route::patch('/users/{user}/toggle', [UserController::class, 'toggleActive'])->name('users.toggle');
        
        Route::resource('facility-categories', FacilityCategoryController::class);
        Route::resource('locations', LocationController::class);
        Route::resource('facilities', FacilityController::class);
        Route::resource('damage-categories', DamageCategoryController::class);
    });

    // Damage Reports
    Route::resource('damage-reports', DamageReportController::class);
    Route::post('/damage-reports/{damage_report}/submit', [DamageReportController::class, 'submit'])->name('damage-reports.submit');
    Route::post('/damage-reports/{damage_report}/verify', [DamageReportController::class, 'verify'])->name('damage-reports.verify');

    // Work Orders
    Route::resource('work-orders', WorkOrderController::class);
    Route::post('/work-orders/{work_order}/start', [WorkOrderController::class, 'startWork'])->name('work-orders.start');
    Route::post('/work-orders/{work_order}/complete', [WorkOrderController::class, 'completeWork'])->name('work-orders.complete');

    // Repair Progress
    Route::post('/work-orders/{work_order}/progress', [RepairProgressController::class, 'store'])->name('repair-progress.store');

    // Reporting (Supervisor and Admin)
    Route::middleware('role:admin,supervisor')->group(function () {
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/export', [ReportController::class, 'exportPdf'])->name('reports.export');
    });
});

require __DIR__.'/auth.php';

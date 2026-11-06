<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan; // ADDED: Required for migrations
use App\Http\Controllers\HomeController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\BorrowRequestController;
use App\Http\Controllers\StaffBorrowRequestController;
use App\Http\Controllers\AdminEquipmentController;
use App\Http\Controllers\AdminRequestController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| TEMPORARY DATABASE SETUP ROUTE (DELETE AFTER USE)
|--------------------------------------------------------------------------
*/
Route::get('/setup-database-secretly', function () {
    // Secret token to prevent others from running this
    if (request('token') !== 'luigi123') {
        abort(403, 'Unauthorized action.');
    }

    try {
        // 1. Create the tables
        Artisan::call('migrate', ['--force' => true]);
        $migrateOutput = Artisan::output();

        // 2. Add the default Admin/Staff users
        Artisan::call('db:seed', ['--force' => true]);
        $seedOutput = Artisan::output();

        return response()->json([
            'status' => 'Success',
            'migrations' => $migrateOutput,
            'seeders' => $seedOutput,
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'Error',
            'message' => $e->getMessage(),
        ], 500);
    }
});

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/equipment', [EquipmentController::class, 'index'])->name('equipment.index');

// REMOVED 'verified' FROM THIS GROUP
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    Route::get('/requests', [BorrowRequestController::class, 'index'])->name('requests.index');
    Route::get('/requests/create', [BorrowRequestController::class, 'create'])->name('requests.create');
    Route::post('/requests', [BorrowRequestController::class, 'store'])->name('requests.store');

    // PROFILE ROUTES
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // STAFF ROUTES
    Route::middleware('staff')->prefix('staff')->name('staff.')->group(function () {
        Route::get('/requests', [StaffBorrowRequestController::class, 'index'])->name('requests.index');
        Route::patch('/requests/{borrowRequest}/approve', [StaffBorrowRequestController::class, 'approve'])->name('requests.approve');
        Route::patch('/requests/{borrowRequest}/reject', [StaffBorrowRequestController::class, 'reject'])->name('requests.reject');
        Route::patch('/requests/{borrowRequest}/return', [StaffBorrowRequestController::class, 'markReturned'])->name('requests.return');
    });

    // ADMIN ROUTES
    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/equipment', [AdminEquipmentController::class, 'index'])->name('equipment.index');
        Route::get('/equipment/create', [AdminEquipmentController::class, 'create'])->name('equipment.create');
        Route::post('/equipment', [AdminEquipmentController::class, 'store'])->name('equipment.store');
        Route::get('/equipment/{equipment}/edit', [AdminEquipmentController::class, 'edit'])->name('equipment.edit');
        Route::put('/equipment/{equipment}', [AdminEquipmentController::class, 'update'])->name('equipment.update');
        Route::delete('/equipment/{equipment}', [AdminEquipmentController::class, 'destroy'])->name('equipment.destroy');
        
        Route::get('/requests', [AdminRequestController::class, 'index'])->name('requests.index');
        Route::patch('/requests/{borrowRequest}/approve', [AdminRequestController::class, 'approve'])->name('requests.approve');
        Route::patch('/requests/{borrowRequest}/reject', [AdminRequestController::class, 'reject'])->name('requests.reject');
        Route::patch('/requests/{borrowRequest}/return', [AdminRequestController::class, 'markReturned'])->name('requests.return');
    });
});

require __DIR__.'/auth.php';
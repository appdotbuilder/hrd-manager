<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\HrdController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/health-check', function () {
    return response()->json([
        'status' => 'ok',
        'timestamp' => now()->toISOString(),
    ]);
})->name('health-check');

Route::get('/', function () {
    return Inertia::render('welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    // Main HRD Dashboard
    Route::get('dashboard', [HrdController::class, 'index'])->name('dashboard');
    
    // Employee Management
    Route::resource('employees', EmployeeController::class);
    
    // Attendance Management
    Route::controller(AttendanceController::class)->group(function () {
        Route::get('attendance', 'index')->name('attendance.index');
        Route::post('attendance', 'store')->name('attendance.store');
        Route::get('attendance/{userId?}', 'show')->name('attendance.show');
    });
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';

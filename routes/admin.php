<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\FacilityController;
use App\Http\Controllers\Admin\BookingController;

// Admin Dashboard
Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');
    
    // Facilities Management Routes
    Route::resource('facilities', FacilityController::class)->names('admin.facilities');
    
    // Booking Management Routes
    Route::resource('bookings', BookingController::class)->except(['create', 'store', 'show'])->names('admin.bookings');
});

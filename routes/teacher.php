<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Teacher\Facilities\FacilityList;
use App\Livewire\Teacher\Facilities\ShowFacility;
use App\Models\Facility;

// Dashboard for teachers
Route::middleware(['auth', 'verified', 'role:teacher'])->prefix('teacher')->group(function () {
    Route::get('/dashboard', function () {
        return view('teacher.dashboard');
    })->name('teacher.dashboard');
    
    // Facility routes for teachers
    Route::get('/facilities', function () {
        return view('teacher.facilities.index');
    })->name('teacher.facilities.index');
    
    Route::get('/facilities/{facility}', function (Facility $facility) {
        return view('teacher.facilities.show', compact('facility'));
    })->name('teacher.facilities.show');
    
    // Add more teacher routes here
});

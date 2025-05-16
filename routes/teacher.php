<?php

use Illuminate\Support\Facades\Route;

// Dashboard for teachers
Route::middleware(['auth', 'verified', 'role:teacher'])->prefix('teacher')->group(function () {
    Route::get('/dashboard', function () {
        return view('teacher.dashboard');
    })->name('teacher.dashboard');
    
    // Add more teacher routes here
});

<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use Illuminate\Support\Facades\Route;

// Auth and login routes
Route::get('/', [SessionController::class, 'create'])->name('login');
Route::post('/', [SessionController::class, 'store'])->name('login');
Route::view('/register', 'auth.register')->name('register');

// Admin routes
Route::get('/admin/dashboard', [AdminController::class, 'index'])
    ->name('admin.dashboard')
    ->middleware('auth');

// Student routes
Route::get('/student/dashboard', [StudentController::class, 'index'])
    ->name('student.dashboard')
    ->middleware('auth');

// Teacher routes
Route::get('/teacher/dashboard', [TeacherController::class, 'index'])
    ->name('teacher.dashboard')
    ->middleware('auth');

// Logout route
Route::get('/logout', [SessionController::class, 'destroy'])->name('logout');

<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use Illuminate\Support\Facades\Route;

Route::get('/', [SessionController::class, 'create'])->name('login');
Route::post('/', [SessionController::class, 'store'])->name('login');
Route::view('/register', 'auth.register')->name('register');

Route::get('/admin/dashboard', [AdminController::class, 'index'])
    ->name('dashboard')
    ->middleware('auth');
Route::get('/student/dashboard', [StudentController::class, 'index'])
    ->name('dashboard')
    ->middleware('auth');
Route::get('/teacher/dashboard', [TeacherController::class, 'index'])
    ->name('dashboard')
    ->middleware('auth');


// logout functionality
Route::get('/logout', [SessionController::class, 'destroy'])->name('logout');

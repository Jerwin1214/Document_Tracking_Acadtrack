<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\StudentRegisterController;
use App\Http\Controllers\TeacherController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\StudentMiddleware;
use App\Http\Middleware\TeacherMiddleware;
use Illuminate\Support\Facades\Route;

// Auth and login routes
Route::get('/', [SessionController::class, 'create'])->name('login');
Route::post('/', [SessionController::class, 'store'])->name('login');
Route::get('/register', [StudentRegisterController::class, 'create'])->name('register');
Route::post('/register', [StudentRegisterController::class, 'store'])->name('register');

// Admin routes
Route::middleware(['auth', AdminMiddleware::class])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])
        ->name('admin.dashboard');
    Route::get('/admin/students/show', [StudentController::class, 'showAllStudents'])
        ->name('admin.students.index');
    Route::get('/admin/students/create', [StudentController::class, 'create'])
        ->name('admin.students.create');
    Route::post('/admin/students', [StudentController::class, 'store'])
        ->name('admin.students.store');
    Route::get('/admin/students/{student}/edit', [StudentController::class, 'edit'])
        ->name('admin.students.edit');
    Route::patch('/admin/students/{student}', [StudentController::class, 'update'])
        ->name('admin.students.update');
    Route::get('/admin/students/{student}', [StudentController::class, 'show'])
        ->name('admin.students.show');
});

// Student routes
Route::middleware(['auth', StudentMiddleware::class])->group(function () {
    Route::get('/student/dashboard', [StudentController::class, 'index'])
        ->name('student.dashboard');
});

// Teacher routes
Route::middleware(['auth', TeacherMiddleware::class])->group(function () {
    Route::get('/teacher/dashboard', [TeacherController::class, 'index'])
        ->name('teacher.dashboard');
});

// Logout route
Route::get('/logout', [SessionController::class, 'destroy'])->name('logout');

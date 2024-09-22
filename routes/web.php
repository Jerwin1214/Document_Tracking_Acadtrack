<?php

// controllers
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\StudentRegisterController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TeacherController;

// middlewares
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\StudentMiddleware;
use App\Http\Middleware\TeacherMiddleware;
use Illuminate\Support\Facades\Route;

// Auth and login routes
Route::get('/', [SessionController::class, 'create'])->name('login.create');
Route::post('/', [SessionController::class, 'store'])->name('login.store');
Route::get('/register', [StudentRegisterController::class, 'create'])->name('register.create');
Route::post('/register', [StudentRegisterController::class, 'store'])->name('register.store');

// Admin routes
Route::middleware(['auth', AdminMiddleware::class])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

    // students
    Route::get('/admin/students/show', [StudentController::class, 'showAllStudents'])->name('admin.students.index');
    Route::get('/admin/students/create', [StudentController::class, 'create'])->name('admin.students.create');
    Route::post('/admin/students', [StudentController::class, 'store'])->name('admin.students.store');
    Route::get('/admin/students/{student}/edit', [StudentController::class, 'edit'])->name('admin.students.edit');
    Route::patch('/admin/students/{student}', [StudentController::class, 'update'])->name('admin.students.update');
    Route::get('/admin/students/{student}', [StudentController::class, 'show'])->name('admin.students.show');
    Route::delete('/admin/students/{student}', [StudentController::class, 'destroy'])->name('admin.students.destroy');

    // teachers
    Route::get('/admin/teachers/show', [TeacherController::class, 'showAllTeachers'])->name('admin.teachers.index');
    Route::get('/admin/teachers/create', [TeacherController::class, 'create'])->name('admin.teachers.create');
    Route::post('/admin/teachers', [TeacherController::class, 'store'])->name('admin.teachers.store');
    Route::get('/admin/teachers/{teacher}', [TeacherController::class, 'show'])->name('admin.teacher.show');
    Route::get('/admin/teachers/{teacher}/edit', [TeacherController::class, 'edit'])->name('admin.teachers.edit');
    Route::patch('/admin/teachers/{teacher}', [TeacherController::class, 'update'])->name('admin.teachers.update');
    Route::delete('/admin/teachers/{teacher}', [TeacherController::class, 'destroy'])->name('admin.teachers.destroy');

    // subjects
    Route::get('/admin/subjects/show', [SubjectController::class, 'showAllSubjects'])->name('admin.subjects.index');
    Route::get('/admin/subjects/create', [SubjectController::class, 'create'])->name('admin.subjects.create');
    Route::post('/admin/subjects', [SubjectController::class, 'store'])->name('admin.subjects.store');
    Route::get('/admin/subjects/{subject}/edit', [SubjectController::class, 'edit'])->name('admin.subjects.edit');
    Route::patch('/admin/subjects/{subject}', [SubjectController::class, 'update'])->name('admin.subjects.update');
    Route::delete('/admin/subjects/{subject}', [SubjectController::class, 'destroy'])->name('admin.subjects.destroy');
    Route::get('/admin/subjects/assign', [SubjectController::class, 'assignTeachersView'])->name('admin.subjects.assignView');
    Route::post('/admin/subjects/assign', [SubjectController::class, 'assignTeachers'])->name('admin.subjects.assign');
    Route::get('/admin/subjects/teachers/{teacher}', [SubjectController::class, 'showAssignedSubjectsForTeacher'])->name('admin.subjects.teachers');
});

// Student routes
Route::middleware(['auth', StudentMiddleware::class])->group(function () {
    Route::get('/student/dashboard', [StudentController::class, 'index'])->name('student.dashboard');
});

// Teacher routes
Route::middleware(['auth', TeacherMiddleware::class])->group(function () {
    Route::get('/teacher/dashboard', [TeacherController::class, 'index'])->name('teacher.dashboard');
});

// Logout route
Route::get('/logout', [SessionController::class, 'destroy'])->name('logout');

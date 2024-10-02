<?php

// controllers
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ClassController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\SubjectController;
use App\Http\Controllers\Admin\TeacherController;

use App\Http\Controllers\Teacher\TeacherStudentController;

use App\Http\Controllers\SessionController;
use App\Http\Controllers\StudentRegisterController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\StudentMiddleware;
use App\Http\Middleware\TeacherMiddleware;
use Illuminate\Support\Facades\Route;

// middlewares

// Auth and login routes
Route::get('/', [SessionController::class, 'create'])->name('login');
Route::post('/', [SessionController::class, 'store'])->name('login');
Route::get('/register', [StudentRegisterController::class, 'create'])->name('register');
Route::post('/register', [StudentRegisterController::class, 'store'])->name('register');

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

    // classes
    Route::get('/admin/class/create', [ClassController::class, 'create'])->name('admin.classes.create');
    Route::post('/admin/class', [ClassController::class, 'store'])->name('admin.classes.store');
    Route::get('/admin/class/show', [ClassController::class, 'index'])->name('admin.classes.index');
    Route::get('/admin/class/{class}', [ClassController::class, 'show'])->name('admin.classes.show');
    Route::get('/admin/class/{class}/edit', [ClassController::class, 'edit'])->name('admin.classes.edit');
    Route::patch('/admin/class/{class}', [ClassController::class, 'update'])->name('admin.classes.update');
    Route::delete('/admin/class/{class}', [ClassController::class, 'destroy'])->name('admin.classes.destroy');
    Route::get('/admin/class/{class}/assign', [ClassController::class, 'assignStudentsView'])->name('admin.classes.assignView');
    Route::post('/admin/class/{class}/assign', [ClassController::class, 'assignStudents'])->name('admin.classes.assign');

    // profile
    Route::get('/admin/profile', [AdminController::class, 'showProfile'])->name('admin.profile');

    // settings
    Route::get('/admin/settings', [AdminController::class, 'showSettings'])->name('admin.settings');
    Route::post('/admin/settings', [AdminController::class, 'updateSettings'])->name('admin.settings.update');

    // messages
    Route::get('/admin/messages', [AdminController::class, 'showMessages'])->name('admin.messages');
    Route::get('/admin/messages/{message}', [AdminController::class, 'showMessage'])->name('admin.messages.show');

});

// Teacher routes
Route::middleware(['auth', TeacherMiddleware::class])->group(function () {
    Route::get('/teacher/dashboard', [TeacherController::class, 'index'])->name('teacher.dashboard');

    // students section
    Route::get('teacher/students/add', [TeacherStudentController::class, 'create'])->name('teacher.students.create')->can('create', \App\Models\Student::class);
    Route::post('/teacher/students', [TeacherStudentController::class, 'store'])->name('teacher.student.store')->can('create', \App\Models\Student::class);
    Route::get('/teacher/students/show', [TeacherStudentController::class, 'showAllStudents'])->name('teacher.students.index');
    Route::get('/teacher/students/{student}', [TeacherStudentController::class, 'show'])->name('teacher.students.show')->can('view', 'student');
    Route::get('/teacher/students/{student}/edit', [TeacherStudentController::class, 'edit'])->name('teacher.students.edit')->can('update', 'student');
    Route::patch('/teacher/students/{student}', [TeacherStudentController::class, 'update'])->name('teacher.students.update')->can('update', 'student');
    Route::delete('/teacher/students/{student}', [TeacherStudentController::class, 'destroy'])->name('teacher.students.destroy');
});

// Student routes
Route::middleware(['auth', StudentMiddleware::class])->group(function () {
    Route::get('/student/dashboard', [StudentController::class, 'index'])->name('student.dashboard');
});


// Logout route
Route::get('/logout', [SessionController::class, 'destroy'])->name('logout');

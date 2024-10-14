<?php

// controllers
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ClassController;
use App\Http\Controllers\Admin\StreamController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\SubjectController;
use App\Http\Controllers\Admin\TeacherController;

use App\Http\Controllers\Student\StudentStdController;
use App\Http\Controllers\Teacher\TeacherMainController;
use App\Http\Controllers\Teacher\TeacherStudentController;
use App\Http\Controllers\Teacher\TeacherAnnouncementController;

use App\Http\Controllers\SessionController;
use App\Http\Controllers\StudentRegisterController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\StudentMiddleware;
use App\Http\Middleware\TeacherMiddleware;

use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Support\Facades\Route;

// middlewares

// testing route for check email body
// Route::get('test', function () {
//     return new App\Mail\AnnouncementPosted(
//         App\Models\Announcement::first()
//     );
// });


// Auth and login routes
Route::get('/', [SessionController::class, 'create'])->name('login');
Route::post('/', [SessionController::class, 'store'])->name('login');
Route::get('/register', [StudentRegisterController::class, 'create'])->name('register');
Route::post('/register', [StudentRegisterController::class, 'store'])->name('register');

// Admin routes
Route::middleware(['auth', AdminMiddleware::class])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

    // students
    Route::get('/students/show', [StudentController::class, 'showAllStudents'])->name('admin.students.index');
    Route::get('/students/create', [StudentController::class, 'create'])->name('admin.students.create');
    Route::post('/students', [StudentController::class, 'store'])->name('admin.students.store');
    Route::get('/students/{student}/edit', [StudentController::class, 'edit'])->name('admin.students.edit');
    Route::patch('/students/{student}', [StudentController::class, 'update'])->name('admin.students.update');
    Route::get('/students/{student}', [StudentController::class, 'show'])->name('admin.students.show');
    Route::delete('/students/{student}', [StudentController::class, 'destroy'])->name('admin.students.destroy');
    Route::post('/students/upload', [StudentController::class, 'uploadStudents'])->name('admin.students.upload');

    // teachers
    Route::get('/teachers/show', [TeacherController::class, 'showAllTeachers'])->name('admin.teachers.index');
    Route::get('/teachers/create', [TeacherController::class, 'create'])->name('admin.teachers.create')->can('create', Teacher::class);
    Route::post('/teachers', [TeacherController::class, 'store'])->name('admin.teachers.store')->can('create', Teacher::class);
    Route::get('/teachers/{teacher}', [TeacherController::class, 'show'])->name('admin.teacher.show');
    Route::get('/teachers/{teacher}/edit', [TeacherController::class, 'edit'])->name('admin.teachers.edit')->can('update', 'teacher');
    Route::patch('/teachers/{teacher}', [TeacherController::class, 'update'])->name('admin.teachers.update')->can('update', 'teacher');
    Route::delete('/teachers/{teacher}', [TeacherController::class, 'destroy'])->name('admin.teachers.destroy')->can('delete', 'teacher');
    Route::get('/teachers/{teacher}/assign-class', [TeacherController::class, 'assignClassView'])->name('admin.teachers.assignView');
    Route::post('/teachers/{teacher}/assign-class', [TeacherController::class, 'assignClasses'])->name('admin.teachers.assign');

    // subjects
    Route::get('/subjects/show', [SubjectController::class, 'showAllSubjects'])->name('admin.subjects.index');
    Route::get('/subjects/create', [SubjectController::class, 'create'])->name('admin.subjects.create');
    Route::post('/subjects', [SubjectController::class, 'store'])->name('admin.subjects.store');
    Route::get('/subjects/{subject}/edit', [SubjectController::class, 'edit'])->name('admin.subjects.edit');
    Route::patch('/subjects/{subject}', [SubjectController::class, 'update'])->name('admin.subjects.update');
    Route::delete('/subjects/{subject}', [SubjectController::class, 'destroy'])->name('admin.subjects.destroy');
    Route::get('/subjects/assign', [SubjectController::class, 'assignTeachersView'])->name('admin.subjects.assignView');
    Route::post('/subjects/assign', [SubjectController::class, 'assignTeachers'])->name('admin.subjects.assign');
    Route::get('/subjects/teachers/{teacher}', [SubjectController::class, 'showAssignedSubjectsForTeacher'])->name('admin.subjects.teachers');
    Route::post('/subjects/upload', [SubjectController::class, 'uploadSubjects'])->name('admin.subjects.upload');

    // subject streams
    Route::get('/streams/show', [StreamController::class, 'index'])->name('admin.streams.index');
    Route::get('/streams/create', [StreamController::class, 'create'])->name('admin.streams.create');
    Route::post('/streams', [StreamController::class, 'store'])->name('admin.streams.store');
    Route::get('/streams/{stream}/edit', [StreamController::class, 'edit'])->name('admin.streams.edit');
    Route::patch('/streams/{stream}', [StreamController::class, 'update'])->name('admin.streams.update');
    Route::delete('/streams/{stream}', [StreamController::class, 'destroy'])->name('admin.streams.destroy');
    Route::get('/streams/{stream}/assign', [StreamController::class, 'assignSubjectsView'])->name('admin.streams.assignView');
    Route::post('/streams/{stream}/assign', [StreamController::class, 'assignSubjects'])->name('admin.streams.assign');

    // classes
    Route::get('/class/create', [ClassController::class, 'create'])->name('admin.classes.create');
    Route::post('/class', [ClassController::class, 'store'])->name('admin.classes.store');
    Route::get('/class/show', [ClassController::class, 'index'])->name('admin.classes.index');
    Route::get('/class/{class}', [ClassController::class, 'show'])->name('admin.classes.show');
    Route::get('/class/{class}/edit', [ClassController::class, 'edit'])->name('admin.classes.edit');
    Route::patch('/class/{class}', [ClassController::class, 'update'])->name('admin.classes.update');
    Route::delete('/class/{class}', [ClassController::class, 'destroy'])->name('admin.classes.destroy');
    Route::get('/class/{class}/assign', [ClassController::class, 'assignStudentsView'])->name('admin.classes.assignView');
    Route::post('/class/{class}/assign', [ClassController::class, 'assignStudents'])->name('admin.classes.assign');

    // profile
    Route::get('/profile', [AdminController::class, 'showProfile'])->name('admin.profile');

    // settings
    Route::get('/settings', [AdminController::class, 'showSettings'])->name('admin.settings');
    Route::post('/settings', [AdminController::class, 'updateSettings'])->name('admin.settings.update');

    // messages
    Route::get('/messages', [AdminController::class, 'showMessages'])->name('admin.messages');
    Route::get('/messages/{message}', [AdminController::class, 'showMessage'])->name('admin.messages.show');
});

// Teacher routes
Route::middleware(['auth', TeacherMiddleware::class])->prefix('teacher')->group(function () {
    Route::get('/dashboard', [TeacherController::class, 'index'])->name('teacher.dashboard');

    // students section
    Route::get('/students/create', [TeacherStudentController::class, 'create'])->name('teacher.students.create')->can('create', Student::class);
    Route::post('/students', [TeacherStudentController::class, 'store'])->name('teacher.student.store')->can('create', Student::class);
    Route::get('/students/show', [TeacherStudentController::class, 'showAllStudents'])->name('teacher.students.index');
    Route::get('/students/{student}', [TeacherStudentController::class, 'show'])->name('teacher.students.show')->can('view', 'student');
    Route::get('/students/{student}/edit', [TeacherStudentController::class, 'edit'])->name('teacher.students.edit')->can('update', 'student');
    Route::patch('/students/{student}', [TeacherStudentController::class, 'update'])->name('teacher.students.update')->can('update', 'student');
    Route::get('/students/{student}/assign', [TeacherStudentController::class, 'assignSubjectsView'])->name('teacher.students.assignView');
    Route::patch('/students/{student}/assign', [TeacherStudentController::class, 'assignSubjects'])->name('teacher.students.assign');

    // announcements
    Route::get('/announcements/create', [TeacherAnnouncementController::class, 'create'])->name('teacher.announcements.create');
    Route::post('/announcements', [TeacherAnnouncementController::class, 'store'])->name('teacher.announcements.store');
    Route::get('/announcements/show', [TeacherAnnouncementController::class, 'index'])->name('teacher.announcements.index');
    Route::get('/announcements/{announcement}', [TeacherAnnouncementController::class, 'show'])->name('teacher.announcements.show');
    Route::get('/announcements/{announcement}/edit', [TeacherAnnouncementController::class, 'edit'])->name('teacher.announcements.edit');
    Route::patch('/announcements/{announcement}', [TeacherAnnouncementController::class, 'update'])->name('teacher.announcements.update');
    Route::delete('/announcements/{announcement}', [TeacherAnnouncementController::class, 'destroy'])->name('teacher.announcements.destroy');

    // profile
    Route::get('/profile', [TeacherMainController::class, 'showProfilePage'])->name('teacher.profile');

    // settings
    Route::get('/settings', [TeacherMainController::class, 'showSettingsPage'])->name('teacher.settings');
    Route::post('/settings', [TeacherMainController::class, 'updateSettings'])->name('teacher.settings.update')->can('update', Teacher::class);
});

// Student routes
Route::middleware(['auth', StudentMiddleware::class])->prefix('student')->group(function () {
    Route::get('/dashboard', [StudentController::class, 'index'])->name('student.dashboard');

    // profile
    Route::get('/profile', [StudentStdController::class, 'showProfilePage'])->name('student.profile');

    // settings
    Route::get('/settings', [StudentStdController::class, 'showSettingsPage'])->name('student.settings')->can('update', Student::class);
    Route::post('/settings', [StudentStdController::class, 'updateSettings'])->name('student.settings.update')->can('update', Student::class);
});


// Logout route
Route::get('/logout', [SessionController::class, 'destroy'])->name('logout');

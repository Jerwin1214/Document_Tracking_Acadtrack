<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use App\Http\Controllers\SessionController;
use App\Http\Controllers\StudentRegisterController;

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\PasswordManagementController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\TeacherController;
use App\Http\Controllers\Admin\SubjectController;
use App\Http\Controllers\Admin\StreamController;
use App\Http\Controllers\Admin\ClassController;
use App\Http\Controllers\Admin\EnrollmentController;
use App\Http\Controllers\Admin\StudentDocumentController;
use App\Http\Controllers\Teacher\TeacherMainController;
use App\Http\Controllers\Admin\TeacherGradeController;
use App\Http\Controllers\Teacher\TeacherStudentController;
use App\Http\Controllers\Teacher\TeacherAnnouncementController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Student\StudentStdController;
use App\Http\Controllers\Teacher\GradeController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\StudentMiddleware;
use App\Http\Middleware\TeacherMiddleware;


use App\Models\Student;
use App\Models\Teacher;

// ===============================
// 🔐 AUTH AND LOGIN ROUTES
// ===============================
Route::get('/', fn () => view('auth.select-role'))->name('select.role');
Route::get('/select-role/{role}', [SessionController::class, 'selectRole'])->name('select.role.set');
Route::get('/login/{role}', [SessionController::class, 'selectRole'])->name('select.role.login');
Route::get('/login', [SessionController::class, 'create'])->name('login');
Route::post('/login', [SessionController::class, 'store'])->name('login.submit');
Route::get('/logout', [SessionController::class, 'destroy'])->name('logout');

// Optional Registration
Route::get('/register', [StudentRegisterController::class, 'create'])->name('register');
Route::post('/register', [StudentRegisterController::class, 'store'])->name('register');

// ===============================
// 🛠 ADMIN ROUTES
// ===============================
Route::middleware(['auth', \App\Http\Middleware\AdminMiddleware::class])
    ->prefix('admin')
    ->group(function () {

    // ✅ DASHBOARD
    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

    // ✅ STUDENT ASSIGNMENT
    Route::get('/students/assign', [StudentController::class, 'showAssignForm'])->name('admin.students.assign.form');
    Route::get('/students/{id}/assign', [StudentController::class, 'assignForm'])->name('admin.students.assignForm');
    Route::post('/students/assignDepartment', [StudentController::class, 'assignDepartment'])->name('admin.students.assignDepartment');

    // // ✅ EVENTS
    // Route::prefix('events')->group(function () {
    //     Route::get('/', [EventController::class, 'index']);
    //     Route::post('/', [EventController::class, 'store']);
    //     Route::put('/{id}', [EventController::class, 'update']);
    //     Route::delete('/{id}', [EventController::class, 'destroy']);
    // });

    // ✅ STUDENT DOCUMENTS & ENROLLMENT
    Route::prefix('students')->group(function () {
        Route::get('{student}/documents', [StudentDocumentController::class, 'index'])
            ->name('admin.students.documents.index');
        Route::patch('documents/{studentDocument}', [StudentDocumentController::class, 'update'])
            ->name('admin.students.documents.update');

    Route::patch('documents/{studentDocument}', [StudentDocumentController::class, 'update'])
         ->name('admin.documents.update');
             // ✅ Checklist for all enrollments
    Route::get('documents/checklist', [StudentDocumentController::class, 'checklist'])
        ->name('admin.documents.checklist');
   // Dashboard to track documents
    Route::get('/documents-dashboard', [EnrollmentController::class, 'documentsDashboard'])
        ->name('admin.documents.dashboard');
        Route::get('/admin/dashboard', [StudentDocumentController::class, 'documentUploadsChart'])
    ->name('admin.dashboard');
        Route::put('/{studentDocument}', [StudentDocumentController::class, 'update'])->name('update');
    Route::delete('{studentDocument}', [StudentDocumentController::class, 'destroy'])
        ->name('admin.documents.destroy');
        Route::put('/admin/documents/update-multiple/{enrollment}', [StudentDocumentController::class, 'updateMultiple'])
    ->name('admin.documents.updateMultiple');
Route::get('/admin/students/documents-dashboard', [App\Http\Controllers\Admin\EnrollmentController::class, 'documentsDashboard'])
    ->name('admin.documents.dashboard')
    ->middleware(['auth', App\Http\Middleware\AdminMiddleware::class]);

    });

    Route::prefix('enrollment')->name('admin.enrollment.')->group(function () {
        Route::get('/', [EnrollmentController::class, 'index'])->name('index');
        Route::get('/add', [EnrollmentController::class, 'addEnrollment'])->name('add');
        Route::post('/store', [EnrollmentController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [EnrollmentController::class, 'edit'])->name('edit');
        Route::patch('/update/{id}', [EnrollmentController::class, 'update'])->name('update');
        Route::patch('/{id}/archive', [EnrollmentController::class, 'archive'])->name('archive');
        Route::patch('/{id}/restore', [EnrollmentController::class, 'restore'])->name('restore');
        Route::get('/{id}', [EnrollmentController::class, 'show'])->name('show');
        Route::post('/{enrollment}/upload-document', [EnrollmentController::class, 'uploadDocument'])
            ->name('uploadDocument');
    });

    // ✅ STUDENTS
    Route::get('/students', [StudentController::class, 'index'])->name('admin.students.index');
    Route::get('/students/create', [StudentController::class, 'create'])->name('admin.students.create');
    Route::post('/students', [StudentController::class, 'store'])->name('admin.students.store');
    Route::get('/students/{student}', [StudentController::class, 'show'])->name('admin.students.show');
    Route::get('/students/{student}/edit', [StudentController::class, 'edit'])->name('admin.students.edit');
    Route::patch('/students/{student}', [StudentController::class, 'update'])->name('admin.students.update');
    Route::delete('/students/{student}', [StudentController::class, 'destroy'])->name('admin.students.destroy');
    Route::post('/students/upload', [StudentController::class, 'uploadStudents'])->name('admin.students.upload');
    Route::patch('/students/{id}/archive', [StudentController::class, 'archive'])->name('admin.students.archive');
    Route::patch('/students/{id}/unarchive', [StudentController::class, 'unarchive'])->name('admin.students.unarchive');
    Route::post('/students/assign', [StudentController::class, 'assign'])->name('admin.students.assign');

    // ✅ TEACHERS
    Route::get('/teachers/show', [TeacherController::class, 'showAllTeachers'])->name('admin.teachers.index');
    Route::get('/teachers/create', [TeacherController::class, 'create'])->name('admin.teachers.create');
    Route::post('/teachers', [TeacherController::class, 'store'])->name('admin.teachers.store');
    Route::get('/teachers/{teacher}', [TeacherController::class, 'show'])->name('admin.teachers.show');
    Route::get('/teachers/{teacher}/edit', [TeacherController::class, 'edit'])->name('admin.teachers.edit');
    Route::patch('/teachers/{teacher}', [TeacherController::class, 'update'])->name('admin.teachers.update');
    Route::patch('/teachers/{id}/archive', [TeacherController::class, 'archive'])->name('admin.teachers.archive');
    Route::patch('/teachers/{id}/unarchive', [TeacherController::class, 'unarchive'])->name('admin.teachers.unarchive');
    Route::get('/teachers/archived', [TeacherController::class, 'showArchived'])->name('admin.teachers.archived');
    Route::get('/teachers/{teacher}/assign-class', [TeacherController::class, 'assignClassForm'])->name('admin.teachers.assignView');
    Route::post('/teachers/{teacher}/assign-class', [TeacherController::class, 'assignClass'])->name('admin.teachers.assignClass');
    Route::get('/teachers/{teacher}/assign-classes', [TeacherController::class, 'assignClassForm'])->name('admin.teachers.assignClassForm');

    // ✅ SUBJECTS
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
    Route::get('/subjects/get-subjects', [SubjectController::class, 'getSubjects'])->name('admin.subjects.getSubjects');

    // ✅ STREAMS
    Route::get('/streams/show', [StreamController::class, 'index'])->name('admin.streams.index');
    Route::get('/streams/create', [StreamController::class, 'create'])->name('admin.streams.create');
    Route::post('/streams', [StreamController::class, 'store'])->name('admin.streams.store');
    Route::get('/streams/{stream}/edit', [StreamController::class, 'edit'])->name('admin.streams.edit');
    Route::patch('/streams/{stream}', [StreamController::class, 'update'])->name('admin.streams.update');
    Route::delete('/streams/{stream}', [StreamController::class, 'destroy'])->name('admin.streams.destroy');
    Route::get('/streams/{stream}/assign', [StreamController::class, 'assignSubjectsView'])->name('admin.streams.assignView');
    Route::post('/streams/{stream}/assign', [StreamController::class, 'assignSubjects'])->name('admin.streams.assign');

    // ✅ CLASSES
    Route::get('/class/show', [ClassController::class, 'index'])->name('admin.classes.index');
    Route::get('/class/create', [ClassController::class, 'create'])->name('admin.classes.create');
    Route::post('/class', [ClassController::class, 'store'])->name('admin.classes.store');
    Route::get('/class/{class}', [ClassController::class, 'show'])->name('admin.classes.show');
    Route::get('/class/{class}/edit', [ClassController::class, 'edit'])->name('admin.classes.edit');
    Route::patch('/class/{class}', [ClassController::class, 'update'])->name('admin.classes.update');
    Route::delete('/class/{class}', [ClassController::class, 'destroy'])->name('admin.classes.destroy');
    Route::get('/class/{class}/assign', [ClassController::class, 'assignStudentsView'])->name('admin.classes.assignView');
    Route::post('/class/{class}/assign', [ClassController::class, 'assignStudents'])->name('admin.classes.assign');

    // ✅ PROFILE & SETTINGS
    Route::get('/profile', [AdminController::class, 'showProfile'])->name('admin.profile');
    Route::put('/profile/update', [AdminController::class, 'updateProfile'])->name('admin.profile.update');
    Route::get('/settings', [AdminController::class, 'showSettings'])->name('admin.settings');
    Route::post('/settings', [AdminController::class, 'updateSettings'])->name('admin.settings.update');
    Route::get('/password', [AdminController::class, 'showPasswordForm'])->name('admin.password');
    Route::put('/password', [AdminController::class, 'updatePassword'])->name('admin.change-password');

    // ✅ PASSWORD MANAGEMENT
    Route::get('/password-management', [PasswordManagementController::class, 'index'])->name('admin.password.manage');
    Route::post('/password-management/reset', [PasswordManagementController::class, 'resetPassword'])->name('admin.password.reset');
    Route::get('/password-management/{user}/reset', [PasswordManagementController::class, 'showPasswordForm'])->name('admin.password.form');
    Route::post('/password-management/{user}/update', [PasswordManagementController::class, 'updatePassword'])->name('admin.password.update');

    // ✅ MESSAGES
    Route::get('/messages', [AdminController::class, 'showMessages'])->name('admin.messages');
    Route::get('/messages/{message}', [AdminController::class, 'showMessage'])->name('admin.messages.show');
});

// ===============================
// 👨‍🏫 TEACHER ROUTES
// ===============================
Route::middleware(['auth', TeacherMiddleware::class])
    ->prefix('teacher')
    ->name('teacher.')
    ->group(function () {

    // Dashboard
    Route::get('/dashboard', [TeacherMainController::class, 'index'])
        ->name('dashboard');

    // Students assigned to this teacher
    Route::get('/students', [TeacherStudentController::class, 'index'])
        ->name('students.index');

    // ✅ View students per class
    Route::get('/classes/{class}/students', [TeacherStudentController::class, 'index'])
        ->name('classes.students');

    Route::get('/classes/{class}/students/ajax', [TeacherStudentController::class, 'getStudentsAjax'])
        ->name('classes.students.ajax');

    // Student Management
    Route::get('/students/create', [TeacherStudentController::class, 'create'])
        ->name('students.create');
    Route::post('/students', [TeacherStudentController::class, 'store'])
        ->name('students.store');

    // View a single student
    Route::get('/students/{student}', [TeacherStudentController::class, 'show'])
        ->name('students.show');

    // Edit student
    Route::get('/students/{student}/edit', [TeacherStudentController::class, 'edit'])
        ->name('students.edit');
    Route::put('/students/{student}', [TeacherStudentController::class, 'update'])
        ->name('students.update');

    // Assign subjects to student
    Route::get('/students/{student}/assign', [TeacherStudentController::class, 'assignSubjectsView'])
        ->name('students.assignView');
    Route::put('/students/{student}/assign', [TeacherStudentController::class, 'assignSubjects'])
        ->name('students.assign');

    // ===============================
    // 📊 GRADES ROUTES
    // ===============================
    Route::prefix('grades')->name('grades.')->group(function () {


        // Show teacher's classes
        Route::get('/', [GradeController::class, 'index'])->name('index');

        // Show subjects for a class
        Route::get('/class/{class}', [GradeController::class, 'classSubjects'])->name('class.subjects');

        // ✅ Added: View all grades for a specific class
        Route::get('/class/{class}/grades', [GradeController::class, 'showClassGrades'])
            ->name('class.grades');

        // Show students for a subject
        Route::get('/class/{class}/subject/{subject}', [GradeController::class, 'subjectStudents'])->name('class.subject.students');


      // ✅ Grading form for a student (all subjects)
        Route::get('/student/{student}/create', [GradeController::class, 'create'])
            ->name('create');

        // Store grades
        Route::post('/store', [GradeController::class, 'store'])->name('store');



    // Manage grades
    Route::get('/', [GradeController::class, 'index'])->name('index');


    // 1️⃣ Select Class → select-class.blade.php
    Route::get('/select-class', [GradeController::class, 'selectClassView'])
        ->name('view.select-class');

    // 2️⃣ Show Subjects for a Class → subjects-view.blade.php
    Route::get('/class/{class}/subjects', [GradeController::class, 'showClassSubjects'])
        ->name('view.class.subjects');

  // 3️⃣ View Grades for Class / Optional Subject → view-class-subject-grades.blade.php
    Route::get('/class/{class}/subject/{subject?}/grades', [GradeController::class, 'showClassSubjectGrades'])
        ->name('view.class.subject.grades');

Route::get('/class/{class}/students', [GradeController::class, 'classStudents'])
    ->name('class.students');


    });






    // ===============================
    // 📢 ANNOUNCEMENTS
    // ===============================
    Route::get('/announcements/show', [TeacherAnnouncementController::class, 'index'])
        ->name('announcements.index');
    Route::get('/announcements/create', [TeacherAnnouncementController::class, 'create'])
        ->name('announcements.create');
    Route::post('/announcements', [TeacherAnnouncementController::class, 'store'])
        ->name('announcements.store');
    Route::get('/announcements/{announcement}', [TeacherAnnouncementController::class, 'show'])
        ->name('announcements.show');
    Route::get('/announcements/{announcement}/edit', [TeacherAnnouncementController::class, 'edit'])
        ->name('announcements.edit');
    Route::patch('/announcements/{announcement}', [TeacherAnnouncementController::class, 'update'])
        ->name('announcements.update');
    Route::delete('/announcements/{announcement}', [TeacherAnnouncementController::class, 'destroy'])
        ->name('announcements.destroy');

    // ===============================
    // ⚙️ PROFILE & SETTINGS
    // ===============================
    Route::get('/profile', [TeacherMainController::class, 'showProfilePage'])
        ->name('profile');
    Route::get('/settings', [TeacherMainController::class, 'showSettingsPage'])
        ->name('settings');
    Route::post('/settings', [TeacherMainController::class, 'updateSettings'])
        ->name('settings.update');

    // ✅ NEW: Update Password Route
    Route::post('/settings/update-password', [TeacherMainController::class, 'updatePassword'])
        ->name('updatePassword');
});

// ===============================
// 🎓 STUDENT ROUTES
// ===============================
// In routes/web.php



Route::get('/student/dashboard', [StudentStdController::class, 'dashboard'])->name('student.dashboard');

Route::middleware(['auth', StudentMiddleware::class])->prefix('student')->group(function () {
    Route::get('/dashboard', [StudentStdController::class, 'dashboard'])->name('student.dashboard');
    Route::get('/profile', [StudentStdController::class, 'showProfilePage'])->name('student.profile');

    // Settings / Change Password
    Route::get('/settings', [StudentStdController::class, 'showSettingsPage'])
        ->name('student.settings');

    Route::post('/settings', [StudentStdController::class, 'updateSettings'])
        ->name('student.updateSettings');
});



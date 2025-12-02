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
use App\Http\Controllers\Admin\PromotionHistoryController;
use App\Http\Controllers\Student\StudentStdController;
use App\Http\Controllers\Teacher\GradeController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\StudentMiddleware;
use App\Http\Middleware\TeacherMiddleware;
use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Auth\ForgotPasswordController;


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
// Route::post('/register', [StudentRegisterController::class, 'store'])->name('register');

// ===============================
// 🔑 FORGOT PASSWORD (Public Access)
// ===============================
Route::get('/forgot-password', [ForgotPasswordController::class, 'showForm'])->name('forgotPassword.form');
Route::post('/forgot-password/send-code', [ForgotPasswordController::class, 'sendCode'])->name('forgotPassword.sendCode');
Route::get('/forgot-password/verify', [ForgotPasswordController::class, 'showVerifyForm'])->name('forgotPassword.verifyForm');
Route::post('/forgot-password/verify', [ForgotPasswordController::class, 'verifyCode'])->name('forgotPassword.verifyCode');
Route::get('/forgot-password/reset', [ForgotPasswordController::class, 'showResetForm'])->name('forgotPassword.resetForm');
Route::post('/forgot-password/reset', [ForgotPasswordController::class, 'resetPassword'])->name('forgotPassword.reset');

// ===============================
// 🛠 ADMIN ROUTES
// ===============================
Route::middleware(['auth', AdminMiddleware::class])
    ->prefix('admin')
    ->group(function () {

//ready print feature reports
Route::get('/dashboard/print-report', [StudentDocumentController::class, 'printReport'])->name('admin.dashboard.printReport');
//print documents
Route::get('/documents/checklist/pdf', [StudentDocumentController::class, 'printChecklist'])->name('admin.documents.checklist.pdf');

// Show the signatory selection page
Route::get('/admin/reports/select-signatory', [StudentDocumentController::class, 'selectSignatory'])
    ->name('admin.reports.selectSignatory');

// Generate PDF report with selected signatory
Route::get('/admin/reports/print', [StudentDocumentController::class, 'printReport'])
    ->name('admin.reports.print');


// Show select signatory page before generating PDF
Route::get('/documents/checklist/select-signatory', [StudentDocumentController::class, 'selectSignatoryForChecklist'])
    ->name('admin.documents.checklist.select-signatory');

// Generate PDF after selecting signatory
Route::get('/documents/checklist/pdf', [StudentDocumentController::class, 'printChecklist'])
    ->name('admin.documents.checklist.pdf');

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


             // ✅ Checklist for all enrollments
    Route::get('documents/checklist', [StudentDocumentController::class, 'checklist'])
        ->name('admin.documents.checklist');
   // Dashboard to track documents
    Route::get('/documents-dashboard', [EnrollmentController::class, 'documentsDashboard'])
        ->name('admin.documents.dashboard');
    //     Route::get('/admin/dashboard', [StudentDocumentController::class, 'documentUploadsChart'])
    // ->name('admin.dashboard');
        Route::put('/{studentDocument}', [StudentDocumentController::class, 'update'])->name('update');
    Route::delete('{studentDocument}', [StudentDocumentController::class, 'destroy'])
        ->name('admin.documents.destroy');
// Route::get('/admin/students/documents-dashboard', [EnrollmentController::class, 'documentsDashboard'])
//     ->name('admin.documents.dashboard')
//     ->middleware(['auth', App\Http\Middleware\AdminMiddleware::class]);

 // Signatories Management
    Route::get('/signatories', [App\Http\Controllers\Admin\SignatoryController::class, 'index'])
        ->name('admin.signatories.index');

    Route::post('/signatories', [App\Http\Controllers\Admin\SignatoryController::class, 'store'])
        ->name('admin.signatories.store');

    Route::get('/signatories/{id}/edit', [App\Http\Controllers\Admin\SignatoryController::class, 'edit'])
        ->name('admin.signatories.edit');

    Route::post('/signatories/{id}', [App\Http\Controllers\Admin\SignatoryController::class, 'update'])
        ->name('admin.signatories.update');

    Route::delete('/signatories/{id}', [App\Http\Controllers\Admin\SignatoryController::class, 'destroy'])
        ->name('admin.signatories.delete');

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

// Upload new document(s) for a student
Route::post('/{enrollment}/upload-document', [EnrollmentController::class, 'uploadDocument'])
    ->name('uploadDocument');

    // ✅ Update multiple existing documents
    Route::post('/{enrollment}/update-multiple-documents', [EnrollmentController::class, 'updateMultiple'])
        ->name('updateMultiple');
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



    // ✅ PROFILE & SETTINGS
    Route::get('/profile', [AdminController::class, 'showProfile'])->name('admin.profile');
    Route::put('/profile/update', [AdminController::class, 'updateProfile'])->name('admin.profile.update');
    Route::get('/settings', [AdminController::class, 'showSettings'])->name('admin.settings');
    Route::post('/settings', [AdminController::class, 'updateSettings'])->name('admin.settings.update');
    Route::get('/password', [AdminController::class, 'showPasswordForm'])->name('admin.password');
    Route::put('/password', [AdminController::class, 'updatePassword'])->name('admin.change-password');
    Route::put('/admin/profile/update-email', [AdminController::class, 'updateEmail'])->name('admin.profile.updateEmail');


    // ✅ PROMOTION HISTORY
Route::get('/promotion-history', [PromotionHistoryController::class, 'index'])->name('admin.promotion-history.index');

//activity logs

Route::get('/activity-logs', [ActivityLogController::class, 'index'])
    ->name('admin.activity-logs.index');


    // ✅ PASSWORD MANAGEMENT
    Route::get('/password-management', [PasswordManagementController::class, 'index'])->name('admin.password.manage');
    Route::post('/password-management/reset', [PasswordManagementController::class, 'resetPassword'])->name('admin.password.reset');
    Route::get('/password-management/{user}/reset', [PasswordManagementController::class, 'showPasswordForm'])->name('admin.password.form');
    Route::post('/password-management/{user}/update', [PasswordManagementController::class, 'updatePassword'])->name('admin.password.update');
});




<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // USERS TABLE (core for relationships)
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->enum('role', ['admin', 'teacher', 'student'])->default('student');
            $table->rememberToken();
            $table->timestamps();
        });

        // ADMINS TABLE
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('role')->default('admin');
            $table->timestamps();
        });

        // ADMIN ACTIVITY LOGS
        Schema::create('admin_activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_id')->nullable()->constrained('admins')->nullOnDelete();
            $table->string('action');
            $table->string('module');
            $table->text('description')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();
        });

        // TEACHERS TABLE
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->string('salutation')->nullable();
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->enum('gender', ['Male', 'Female'])->nullable();
            $table->date('dob')->nullable();
            $table->string('address')->nullable();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });

        // STUDENTS TABLE
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->enum('gender', ['Male', 'Female'])->nullable();
            $table->date('dob')->nullable();
            $table->string('address')->nullable();
            $table->string('lrn', 12)->unique();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->enum('status', ['active', 'archived'])->default('active');
            $table->timestamps();
        });

        // GUARDIANS TABLE
        Schema::create('guardians', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('middle_initial')->nullable();
            $table->string('last_name');
            $table->string('address')->nullable();
            $table->string('phone')->nullable();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->timestamps();
        });

        // DOCUMENTS MASTER LIST
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        // STUDENT DOCUMENTS (many-to-many)
        Schema::create('student_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('document_id')->constrained('documents')->cascadeOnDelete();
            $table->string('file_path')->nullable();
            $table->enum('status', ['Pending', 'Submitted'])->default('Pending');
            $table->timestamps();
        });

        // ENROLLMENTS TABLE
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();
            $table->enum('status', ['active', 'archived'])->default('active');
            $table->string('school_year');
            $table->string('grade_level')->nullable();
            $table->boolean('with_lrn')->default(false);
            $table->boolean('returning')->default(false);
            $table->string('last_name')->nullable();
            $table->string('first_name')->nullable();
            $table->string('middle_name')->nullable();
            $table->string('extension_name')->nullable();
            $table->string('psa_birth_cert_no')->nullable();
            $table->string('lrn')->nullable();
            $table->date('birthdate')->nullable();
            $table->string('place_of_birth')->nullable();
            $table->enum('sex', ['Male', 'Female'])->nullable();
            $table->integer('age')->nullable();
            $table->string('mother_tongue')->nullable();
            $table->string('ip_specify')->nullable();
            $table->string('current_barangay')->nullable();
            $table->string('current_city')->nullable();
            $table->string('current_province')->nullable();
            $table->string('current_country')->nullable();
            $table->string('guardian_first_name')->nullable();
            $table->string('guardian_last_name')->nullable();
            $table->string('guardian_contact')->nullable();
            $table->timestamps();
        });

        // CLASSES TABLE
        Schema::create('classes', function (Blueprint $table) {
            $table->id();
            $table->string('department')->nullable();
            $table->string('year_level')->nullable();
            $table->string('section')->nullable();
            $table->foreignId('teacher_id')->constrained('teachers')->cascadeOnDelete();
            $table->string('name');
            $table->string('year');
            $table->timestamps();
        });

        // CLASS_STUDENT (pivot)
        Schema::create('class_student', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_id')->constrained('classes')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->timestamps();
        });

        // SUBJECTS TABLE
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->nullable();
            $table->string('department')->nullable();
            $table->timestamps();
        });

        // CLASS_SUBJECT (pivot)
        Schema::create('class_subject', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_id')->constrained('classes')->cascadeOnDelete();
            $table->foreignId('subject_id')->constrained('subjects')->cascadeOnDelete();
            $table->timestamps();
        });

        // ACTIVITY LOGS (generic system-wide)
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('action');
            $table->string('model');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // CACHE & LOCKS
        Schema::create('cache', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->mediumText('value');
            $table->integer('expiration');
        });

        Schema::create('cache_locks', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->string('owner');
            $table->integer('expiration');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cache_locks');
        Schema::dropIfExists('cache');
        Schema::dropIfExists('activity_logs');
        Schema::dropIfExists('class_subject');
        Schema::dropIfExists('subjects');
        Schema::dropIfExists('class_student');
        Schema::dropIfExists('classes');
        Schema::dropIfExists('enrollments');
        Schema::dropIfExists('student_documents');
        Schema::dropIfExists('documents');
        Schema::dropIfExists('guardians');
        Schema::dropIfExists('students');
        Schema::dropIfExists('teachers');
        Schema::dropIfExists('admin_activity_logs');
        Schema::dropIfExists('admins');
        Schema::dropIfExists('users');
    }
};

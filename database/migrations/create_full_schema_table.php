<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // USER ROLES
        Schema::create('user_roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // Admin, Teacher, Student
            $table->timestamps();
        });

        // USERS
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->foreignId('role_id')->nullable()->constrained('user_roles')->nullOnDelete();
            $table->rememberToken();
            $table->timestamps();
        });

        // ADMINS
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('role_id')->nullable()->constrained('user_roles')->nullOnDelete();
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

        // ACTIVITY LOGS
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('action');
            $table->string('model');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // CLASSES
        Schema::create('classes', function (Blueprint $table) {
            $table->id();
            $table->string('department')->nullable();
            $table->string('year_level')->nullable();
            $table->string('section')->nullable();
            $table->string('name');
            $table->string('year');
            $table->timestamps();
        });

        // DOCUMENTS
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        // STUDENT DOCUMENTS
        Schema::create('student_documents', function (Blueprint $table) {
            $table->id();
            $table->string('student_name')->nullable();
            $table->foreignId('document_id')->constrained('documents')->cascadeOnDelete();
            $table->string('file_path')->nullable();
            $table->enum('status', ['Pending', 'Submitted'])->default('Pending');
            $table->timestamps();
        });

        // ENROLLMENTS
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();
            $table->enum('status', ['active', 'archived'])->default('active');
            $table->string('school_year');
            $table->string('grade_level')->nullable();
            $table->boolean('with_lrn')->default(false);
            $table->boolean('returning')->default(false);
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('middle_name')->nullable();
            $table->date('birthdate')->nullable();
            $table->string('place_of_birth')->nullable();
            $table->enum('sex', ['Male', 'Female'])->nullable();
            $table->integer('age')->nullable();
            $table->timestamps();
        });

        // PASSWORD RESET (Laravel 8 legacy)
        Schema::create('password_resets', function (Blueprint $table) {
            $table->string('email')->index();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        // PASSWORD RESET TOKENS (Laravel 10+)
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        // PROMOTION HISTORIES
        Schema::create('promotion_histories', function (Blueprint $table) {
            $table->id();
            $table->string('student_name');
            $table->string('previous_grade');
            $table->string('promoted_to');
            $table->timestamps();
        });

        // CACHE TABLES
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

        // SESSIONS
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('cache_locks');
        Schema::dropIfExists('cache');
        Schema::dropIfExists('promotion_histories');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('password_resets');
        Schema::dropIfExists('enrollments');
        Schema::dropIfExists('student_documents');
        Schema::dropIfExists('documents');
        Schema::dropIfExists('classes');
        Schema::dropIfExists('activity_logs');
        Schema::dropIfExists('admin_activity_logs');
        Schema::dropIfExists('admins');
        Schema::dropIfExists('users');
        Schema::dropIfExists('user_roles');
    }
};

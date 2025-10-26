<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // activity_logs
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('action');
            $table->string('model');
            $table->text('description')->nullable();
            $table->timestamps(0);
        });

//         // admins
// Schema::create('users', function (Blueprint $table) {
//     $table->id();
//     $table->string('user_id')->unique(); // for login
//     $table->string('password');
//     $table->unsignedBigInteger('role_id'); // 1 = admin
//     $table->string('name')->nullable(); // optional
//     $table->rememberToken();
//     $table->timestamps();
// });


        // admin_activity_logs
        Schema::create('admin_activity_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->string('action');
            $table->string('module');
            $table->text('description')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestamps(0);
        });

        // cache
        Schema::create('cache', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->mediumText('value');
            $table->integer('expiration');
        });

        // cache_locks
        Schema::create('cache_locks', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->string('owner');
            $table->integer('expiration');
        });

        // classes
        Schema::create('classes', function (Blueprint $table) {
            $table->id();
            $table->string('department')->nullable();
            $table->string('year_level')->nullable();
            $table->string('section')->nullable();
            $table->unsignedBigInteger('grade_id')->nullable();
            $table->unsignedBigInteger('teacher_id');
            $table->unsignedBigInteger('subject_stream_id')->nullable();
            $table->string('name');
            $table->string('year');
            $table->timestamps(0);
        });

        // documents
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps(0);
        });

        // enrollments
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
            $table->string('learner_reference_no')->nullable();
            $table->date('birthdate')->nullable();
            $table->string('place_of_birth')->nullable();
            $table->enum('sex', ['Male', 'Female'])->nullable();
            $table->integer('age')->nullable();
            $table->string('mother_tongue')->nullable();
            $table->string('ip_specify')->nullable();
            $table->string('household_id_no')->nullable();
            $table->string('disability_type')->nullable();
            $table->string('current_house_no')->nullable();
            $table->string('current_street')->nullable();
            $table->string('current_barangay')->nullable();
            $table->string('current_city')->nullable();
            $table->string('current_province')->nullable();
            $table->string('current_country')->nullable();
            $table->string('current_zip')->nullable();
            $table->boolean('same_address')->default(true);
            $table->string('permanent_house_no')->nullable();
            $table->string('permanent_street')->nullable();
            $table->string('permanent_barangay')->nullable();
            $table->string('permanent_city')->nullable();
            $table->string('permanent_province')->nullable();
            $table->string('permanent_country')->nullable();
            $table->string('permanent_zip')->nullable();
            $table->string('father_last_name')->nullable();
            $table->string('father_first_name')->nullable();
            $table->string('father_middle_name')->nullable();
            $table->string('father_contact')->nullable();
            $table->string('mother_last_name')->nullable();
            $table->string('mother_first_name')->nullable();
            $table->string('mother_middle_name')->nullable();
            $table->string('mother_contact')->nullable();
            $table->string('guardian_last_name')->nullable();
            $table->string('guardian_first_name')->nullable();
            $table->string('guardian_middle_name')->nullable();
            $table->string('guardian_contact')->nullable();
            $table->timestamps(0);
            $table->string('indigenous_people')->nullable();
            $table->string('fourps_beneficiary')->nullable();
            $table->string('learner_with_disability')->nullable();
        });

        // password_resets
        Schema::create('password_resets', function (Blueprint $table) {
            $table->string('email')->index();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        // password_reset_tokens
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email');
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        // promotion_histories
        Schema::create('promotion_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('enrollment_id')->nullable();
            $table->string('student_name');
            $table->string('previous_grade_level');
            $table->string('new_grade_level');
            $table->string('school_year');
            $table->timestamp('promoted_at')->useCurrent();
            $table->timestamps(0);
        });

        // sessions
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity');
        });

        // student_documents
        Schema::create('student_documents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('enrollment_id');
            $table->unsignedBigInteger('document_id');
            $table->enum('status', ['Submitted','Missing','Pending'])->default('Pending');
            $table->string('file_path')->nullable();
            $table->text('remarks')->nullable();
            $table->date('submitted_at')->nullable();
            $table->timestamps(0);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_documents');
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('promotion_histories');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('password_resets');
        Schema::dropIfExists('enrollments');
        Schema::dropIfExists('documents');
        Schema::dropIfExists('classes');
        Schema::dropIfExists('cache_locks');
        Schema::dropIfExists('cache');
        Schema::dropIfExists('admin_activity_logs');
        Schema::dropIfExists('admins');
        Schema::dropIfExists('activity_logs');
    }
};

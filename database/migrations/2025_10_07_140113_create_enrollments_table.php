<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();
            $table->string('school_year');
            $table->string('grade_level_to_enroll');
            $table->boolean('with_lrn')->default(false);
            $table->boolean('returning')->default(false);

            // Learner Information
            $table->string('psa_birth_cert_no')->nullable();
            $table->string('lrn')->nullable();
            $table->string('learner_reference_no')->nullable();
            $table->date('birthdate')->nullable();
            $table->string('place_of_birth')->nullable();
            $table->enum('sex', ['Male', 'Female'])->nullable();
            $table->integer('age')->nullable();
            $table->string('mother_tongue')->nullable();

            $table->boolean('is_ip')->default(false);
            $table->string('ip_specify')->nullable();
            $table->boolean('is_4ps_beneficiary')->default(false);
            $table->string('household_id_no')->nullable();

            $table->boolean('is_pwd')->default(false);
            $table->string('disability_type')->nullable();

            // Addresses
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

            // Parent/Guardian Info
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

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('enrollments');
    }
};

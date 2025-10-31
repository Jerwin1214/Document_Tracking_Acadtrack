<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('enrollments', function (Blueprint $table) {
            $table->string('extension_name', 50)->nullable();
            $table->string('psa_birth_cert_no')->nullable();
            $table->string('mother_tongue')->nullable();

            // Current address
            $table->string('current_house_no')->nullable();
            $table->string('current_street')->nullable();
            $table->string('current_barangay')->nullable();
            $table->string('current_city')->nullable();
            $table->string('current_province')->nullable();
            $table->string('current_country')->nullable();
            $table->string('current_zip', 10)->nullable();

            // Permanent address
            $table->string('permanent_house_no')->nullable();
            $table->string('permanent_street')->nullable();
            $table->string('permanent_barangay')->nullable();
            $table->string('permanent_city')->nullable();
            $table->string('permanent_province')->nullable();
            $table->string('permanent_country')->nullable();
            $table->string('permanent_zip', 10)->nullable();

            // Father info
            $table->string('father_first_name')->nullable();
            $table->string('father_middle_name')->nullable();
            $table->string('father_last_name')->nullable();
            $table->string('father_contact', 20)->nullable();

            // Mother info
            $table->string('mother_first_name')->nullable();
            $table->string('mother_middle_name')->nullable();
            $table->string('mother_last_name')->nullable();
            $table->string('mother_contact', 20)->nullable();

            // Guardian info
            $table->string('guardian_first_name')->nullable();
            $table->string('guardian_middle_name')->nullable();
            $table->string('guardian_last_name')->nullable();
            $table->string('guardian_contact', 20)->nullable();

            // Other info
            $table->string('indigenous_people')->nullable();
            $table->string('fourps_beneficiary')->nullable();
            $table->string('learner_with_disability')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('enrollments', function (Blueprint $table) {
            $table->dropColumn([
                'extension_name',
                'psa_birth_cert_no',
                'mother_tongue',
                'current_house_no',
                'current_street',
                'current_barangay',
                'current_city',
                'current_province',
                'current_country',
                'current_zip',
                'permanent_house_no',
                'permanent_street',
                'permanent_barangay',
                'permanent_city',
                'permanent_province',
                'permanent_country',
                'permanent_zip',
                'father_first_name',
                'father_middle_name',
                'father_last_name',
                'father_contact',
                'mother_first_name',
                'mother_middle_name',
                'mother_last_name',
                'mother_contact',
                'guardian_first_name',
                'guardian_middle_name',
                'guardian_last_name',
                'guardian_contact',
                'indigenous_people',
                'fourps_beneficiary',
                'learner_with_disability',
            ]);
        });
    }
};

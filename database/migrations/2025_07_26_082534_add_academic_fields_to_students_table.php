<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('students', function (Blueprint $table) {
        $table->string('department')->nullable();  // Kindergarten, Elementary, High School
        $table->string('year_level')->nullable();  // e.g. Grade 1, Grade 7, Grade 11
        $table->string('section')->nullable();     // e.g. A, B, STEM, HUMSS
    });
}

public function down()
{
    Schema::table('students', function (Blueprint $table) {
        $table->dropColumn(['department', 'year_level', 'section']);
    });
}

};

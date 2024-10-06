<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('classes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('grade_id')->constrained('grades');
            $table->foreignId('teacher_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('subject_stream_id')->constrained('subject_streams')->onDelete('cascade')->onUpdate('cascade');
            $table->string('name');
            $table->year('year');
            $table->timestamps();
        });

        Schema::create('class_student', function(Blueprint $table) {
            $table->id();
            $table->foreignId('class_id')->constrained('classes')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
// 2024_09_28_061644
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('class');
    }
};

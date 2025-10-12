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
        Schema::create('student_teacher', function (Blueprint $table) {
            $table->id();

            // Foreign keys
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('teacher_id');

            // Optional: for future flexibility
            $table->string('role')->nullable(); // e.g., adviser, co-teacher, subject teacher

            $table->timestamps();

            // Constraints
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            $table->foreign('teacher_id')->references('id')->on('teachers')->onDelete('cascade');

            $table->unique(['student_id', 'teacher_id']); // prevent duplicates
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_teacher');
    }
};

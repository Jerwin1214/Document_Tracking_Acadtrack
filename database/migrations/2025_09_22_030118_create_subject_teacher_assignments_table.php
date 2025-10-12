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
    Schema::create('subject_teacher_assignments', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('teacher_id');
        $table->string('level'); // kindergarten, elementary, junior_high, senior_high
        $table->string('grade')->nullable(); // 1-6, 7-12
        $table->string('strand')->nullable(); // STEM, ABM, etc
        $table->string('subject'); // subject name or column name
        $table->timestamps();

        $table->foreign('teacher_id')->references('id')->on('teachers')->onDelete('cascade');
    });
}

public function down(): void
{
    Schema::dropIfExists('subject_teacher_assignments');
}

};

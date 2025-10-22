<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('promotion_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('enrollment_id')->nullable();
            $table->string('student_name');
            $table->string('previous_grade_level');
            $table->string('new_grade_level');
            $table->string('school_year');
            $table->timestamp('promoted_at')->useCurrent();
            $table->timestamps();

            $table->foreign('enrollment_id')->references('id')->on('enrollments')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('promotion_histories');
    }
};

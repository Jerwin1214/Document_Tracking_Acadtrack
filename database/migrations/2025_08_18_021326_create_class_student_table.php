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
    Schema::create('class_student', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('class_id');
        $table->unsignedBigInteger('student_id');
        $table->timestamps();

        // Foreign keys
        $table->foreign('class_id')->references('id')->on('classes')->onDelete('cascade');
        $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
    });
}

};

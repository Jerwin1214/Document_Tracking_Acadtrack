<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->enum('gender', ['Male', 'Female']);
            $table->string('lrn', 12)->unique();
            $table->date('dob');
            $table->string('address');
            $table->timestamps();

            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('guardian_id')->nullable()->constrained('guardians')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};

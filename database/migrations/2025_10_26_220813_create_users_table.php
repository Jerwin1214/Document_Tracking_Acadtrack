<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('user_id')->unique(); // login ID
            $table->string('password');
            $table->unsignedTinyInteger('role_id')->default(1); // 1 = admin, 2 = teacher, 3 = student
            $table->rememberToken();
            $table->timestamps(0);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};

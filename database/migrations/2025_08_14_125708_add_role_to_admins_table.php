<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up(): void
{
    Schema::table('admins', function (Blueprint $table) {
        $table->unsignedBigInteger('role_id')->nullable()->after('id');
        $table->foreign('role_id')->references('id')->on('user_roles')->onDelete('set null');
    });
}


    public function down(): void
    {
        Schema::dropIfExists('user_roles');
    }
};

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
    Schema::create('admin_activity_logs', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('admin_id')->nullable();
        $table->string('action');
        $table->string('module'); // e.g. Enrollment, Document, Student, etc.
        $table->text('description')->nullable();
        $table->ipAddress('ip_address')->nullable();
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_activity_logs');
    }
};

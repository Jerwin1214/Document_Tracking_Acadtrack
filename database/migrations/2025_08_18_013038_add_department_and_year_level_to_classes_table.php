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
    Schema::table('classes', function (Blueprint $table) {
        $table->string('department')->nullable()->after('id');
        $table->string('year_level')->nullable()->after('department');
    });
}

public function down(): void
{
    Schema::table('classes', function (Blueprint $table) {
        $table->dropColumn(['department', 'year_level']);
    });
}

};

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


        try {
            //code...
            // create a table named 'user_roles'
            Schema::create('user_roles', function (Blueprint $table) {
                $table->id();
                $table->string('name');
            });
        } catch (\Exception $th) {
            //throw $th;
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_roles');
    }
    // 2024_09_16_172203_create_user_roles_table
    // 2024_09_16_155548_create_students_table
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('teachers', function (Blueprint $table) {
            if (!Schema::hasColumn('teachers', 'gender')) {
                $table->enum('gender', ['Male', 'Female'])->after('last_name');
            }
            if (!Schema::hasColumn('teachers', 'address')) {
                $table->string('address', 255)->after('dob');
            }
            if (!Schema::hasColumn('teachers', 'status')) {
                $table->enum('status', ['active', 'archived'])->default('active')->after('address');
            }
        });
    }

    public function down()
    {
        Schema::table('teachers', function (Blueprint $table) {
            if (Schema::hasColumn('teachers', 'gender')) {
                $table->dropColumn('gender');
            }
            if (Schema::hasColumn('teachers', 'address')) {
                $table->dropColumn('address');
            }
            if (Schema::hasColumn('teachers', 'status')) {
                $table->dropColumn('status');
            }
        });
    }
};

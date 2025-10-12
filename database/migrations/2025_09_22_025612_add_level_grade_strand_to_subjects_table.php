<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
  public function up()
{
    Schema::table('subjects', function (Blueprint $table) {
        $table->string('level')->nullable()->after('description'); // kindergarten, elementary, junior_high, senior_high
        $table->string('grade')->nullable()->after('level'); // 1-12
        $table->string('strand')->nullable()->after('grade'); // STEM, ABM, HUMSS, GAS
    });
}

public function down()
{
    Schema::table('subjects', function (Blueprint $table) {
        $table->dropColumn(['level', 'grade', 'strand']);
    });
}

};

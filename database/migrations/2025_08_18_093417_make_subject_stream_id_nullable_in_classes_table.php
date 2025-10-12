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
    Schema::table('classes', function (Blueprint $table) {
        $table->unsignedBigInteger('subject_stream_id')->nullable()->change();
    });
}

public function down()
{
    Schema::table('classes', function (Blueprint $table) {
        $table->unsignedBigInteger('subject_stream_id')->nullable(false)->change();
    });
}

};

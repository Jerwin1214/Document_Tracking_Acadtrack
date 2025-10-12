<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sections', function (Blueprint $table) {
            $table->id();
            $table->string('section');     // e.g. "St. Mary"
            $table->string('year_level');  // e.g. "Kindergarten", "Grade 1", etc.
            $table->string('strand')->nullable(); // For SHS (ABM, STEM, etc.)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sections');
    }
};

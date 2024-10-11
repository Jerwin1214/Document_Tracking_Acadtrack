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
        Schema::create('subject_streams', function (Blueprint $table) {
            $table->id();
            $table->string('stream_name');
            $table->string('stream_code')->nullable();
            $table->text('stream_description')->nullable();
            $table->timestamps();
        });

        Schema::create('subject_stream_subject', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subject_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('subject_stream_id')->constrained('subject_streams')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subject_streams');
    }
};

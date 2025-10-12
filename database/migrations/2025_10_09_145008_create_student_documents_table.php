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
Schema::create('student_documents', function (Blueprint $table) {
    $table->id();
    $table->foreignId('student_id')->constrained()->onDelete('cascade');
    $table->foreignId('document_id')->constrained()->onDelete('cascade');
    $table->enum('status', ['Complete', 'Missing', 'Pending'])->default('Pending');
    $table->string('file_path')->nullable(); // uploaded file
    $table->text('remarks')->nullable();
    $table->date('submitted_at')->nullable();
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_documents');
    }
};

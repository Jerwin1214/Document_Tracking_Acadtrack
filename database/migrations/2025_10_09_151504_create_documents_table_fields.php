<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        // Optional: seed default documents
        $defaultDocuments = [
            '2x2 Picture',
            'PSA',
            'Photocopy and Original Card',
            'Baptismal Certificate',
            'Good Moral'
        ];

        foreach ($defaultDocuments as $doc) {
            DB::table('documents')->insert([
                'name' => $doc,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
   public function up(): void
{
    Schema::table('students', function (Blueprint $table) {
        // Add student_id column if it doesn't exist
        if (!Schema::hasColumn('students', 'student_id')) {
            $table->string('student_id', 9)->unique()->after('id');
        }

        // We won't touch LRN uniqueness since it's already unique
    });
}


   public function down(): void
{
    Schema::table('students', function (Blueprint $table) {
        $table->dropUnique(['student_id']);
        $table->dropColumn('student_id');
    });
}
};


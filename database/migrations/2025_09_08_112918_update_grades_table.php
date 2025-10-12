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
    Schema::table('grades', function (Blueprint $table) {
        $table->unsignedBigInteger('student_id')->after('id');
        $table->unsignedBigInteger('subject_id')->after('student_id');
        $table->unsignedBigInteger('teacher_id')->after('subject_id');
        $table->string('quarter')->after('teacher_id'); // e.g., 1st, 2nd, 3rd, 4th
        $table->integer('grade')->after('quarter');
        $table->string('remarks')->nullable()->after('grade');

        // Foreign keys (optional but recommended)
        $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
        $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('cascade');
        $table->foreign('teacher_id')->references('id')->on('teachers')->onDelete('cascade');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down()
{
    Schema::table('grades', function (Blueprint $table) {
        $table->dropForeign(['student_id']);
        $table->dropForeign(['subject_id']);
        $table->dropForeign(['teacher_id']);
        $table->dropColumn(['student_id', 'subject_id', 'teacher_id', 'quarter', 'grade', 'remarks']);
    });
}

};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->unsignedBigInteger('class_id')->nullable()->after('id');
            $table->foreign('class_id')->references('id')->on('classes')->onDelete('set null');
        });
    }

   public function down()
{
    Schema::table('students', function (Blueprint $table) {
        // Only drop foreign key if it exists
        $sm = Schema::getConnection()->getDoctrineSchemaManager();
        $foreignKeys = $sm->listTableForeignKeys('students');
        foreach ($foreignKeys as $fk) {
            if (in_array('class_id', $fk->getLocalColumns())) {
                $table->dropForeign($fk->getName());
            }
        }

        // Only drop column if it exists
        if (Schema::hasColumn('students', 'class_id')) {
            $table->dropColumn('class_id');
        }
    });
}

};

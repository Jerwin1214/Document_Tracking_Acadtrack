<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SectionSeeder extends Seeder
{
    public function run()
    {
        $sections = [
            // Kindergarten
            ['year_level' => 'Kindergarten', 'section' => 'St. Mary'],

            // Elementary
            ['year_level' => 'Grade 1', 'section' => 'St. Therese'],
            ['year_level' => 'Grade 2', 'section' => 'St. Claire'],
            ['year_level' => 'Grade 3', 'section' => 'St. Francis'],
            ['year_level' => 'Grade 4', 'section' => 'St. John'],
            ['year_level' => 'Grade 5', 'section' => 'St. James'],
            ['year_level' => 'Grade 6', 'section' => 'St. Pedro Calungsod'],

            // Junior High
            ['year_level' => 'Grade 7', 'section' => 'St. Mark'],
            ['year_level' => 'Grade 8', 'section' => 'St. Thomas'],
            ['year_level' => 'Grade 9', 'section' => 'St. Ignatius'],
            ['year_level' => 'Grade 10', 'section' => 'St. Vladimir'],

            // Senior High (Grade 11)
            ['year_level' => 'Grade 11', 'section' => 'St. Philomere', 'strand' => 'ABM'],
            ['year_level' => 'Grade 11', 'section' => 'St. Philomere', 'strand' => 'STEM'],
            ['year_level' => 'Grade 11', 'section' => 'St. Philomere', 'strand' => 'HUMSS'],
            ['year_level' => 'Grade 11', 'section' => 'St. Philomere', 'strand' => 'GAS'],

            // Senior High (Grade 12)
            ['year_level' => 'Grade 12', 'section' => 'St. Magdalene', 'strand' => 'ABM'],
            ['year_level' => 'Grade 12', 'section' => 'St. Magdalene', 'strand' => 'STEM'],
            ['year_level' => 'Grade 12', 'section' => 'St. Magdalene', 'strand' => 'HUMSS'],
            ['year_level' => 'Grade 12', 'section' => 'St. Magdalene', 'strand' => 'GAS'],
        ];

        DB::table('sections')->insert($sections);
    }
}

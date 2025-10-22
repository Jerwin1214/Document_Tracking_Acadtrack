<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Enrollment;

class PromoteStudents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * You’ll run it like this:
     * php artisan students:promote
     */
    protected $signature = 'students:promote';

    /**
     * The console command description.
     */
    protected $description = 'Automatically promote students to the next grade level at the start of a new school year.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $promotionMap = [
            'Kindergarten' => 'Grade 1',
            'Grade 1' => 'Grade 2',
            'Grade 2' => 'Grade 3',
            'Grade 3' => 'Grade 4',
            'Grade 4' => 'Grade 5',
            'Grade 5' => 'Grade 6',
            'Grade 6' => 'Grade 7',
            'Grade 7' => 'Grade 8',
            'Grade 8' => 'Grade 9',
            'Grade 9' => 'Grade 10',
            'Grade 10' => 'Grade 11',
            'Grade 11' => 'Grade 12',
            'Grade 12' => 'Graduated', // end level
        ];

        $students = Enrollment::where('status', 'active')->get();

        $count = 0;
        foreach ($students as $student) {
            $currentLevel = $student->grade_level;

            if (isset($promotionMap[$currentLevel])) {
                $student->grade_level = $promotionMap[$currentLevel];
                $student->save();
                $count++;
            }
        }

        $this->info("✅ $count students have been promoted to the next grade level!");
    }
}

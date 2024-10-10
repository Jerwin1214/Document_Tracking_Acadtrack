<?php

namespace Database\Seeders;

use App\Models\Guardian;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        DB::table('user_roles')->insert([
            ['name' => 'Admin'],
            ['name' => 'Teacher'],
            ['name' => 'Student'],
        ]);

        DB::table('users')->insert([
            [
                'email' => 'test@admin.com',
                'role_id' => 1,
                'password' => Hash::make('admin123'),
                'created_at' => now(),
            ],
            [
                'email' => 'test@teacher.com',
                'password' => Hash::make('teacher123'),
                'role_id' => 2,
                'created_at' => now(),
            ],
            [
                'email' => 'test@student.com',
                'password' => Hash::make('student123'),
                'role_id' => 3,
                'created_at' => now(),
            ],
        ]);

        // for test admin
        DB::table('admins')->insert([
            [
                'user_id' => User::first()->id,
                'name' => 'Test Admin',
                'created_at' => now(),
            ],
        ]);

        // for test teacher
        DB::table('teachers')->insert([
            [
                'user_id' => User::find(2)->id,
                'salutation' => 'Mr.',
                'initials' => 'T.',
                'first_name' => 'Test',
                'last_name' => 'Teacher',
                'nic' => '123456789V',
                'dob' => '1990-01-01',
                'created_at' => now(),
            ],
        ]);


        // for test guardian
        DB::table('guardians')->insert([
            [
                'initials' => 'T.',
                'first_name' => 'Test',
                'last_name' => 'Guardian',
                'nic' => '123456789V',
                'phone_number' => '0712345678',
                'created_at' => now(),
            ],
        ]);

        // for test student
        DB::table('students')->insert([
            [
                'user_id' => User::find(3)->id,
                'first_name' => 'Test',
                'last_name' => 'Student',
                'gender' => 'Male',
                'nic' => '987654321V',
                'dob' => '2000-01-01',
                'guardian_id' => Guardian::first()->id,
                'created_at' => now(),
            ]
        ]);

        // for grade table
        DB::table('grades')->insert([
            ['name' => 'Grade 1', 'created_at' => now()],
            ['name' => 'Grade 2', 'created_at' => now()],
            ['name' => 'Grade 3', 'created_at' => now()],
            ['name' => 'Grade 4', 'created_at' => now()],
            ['name' => 'Grade 5', 'created_at' => now()],
            ['name' => 'Grade 6', 'created_at' => now()],
            ['name' => 'Grade 7', 'created_at' => now()],
            ['name' => 'Grade 8', 'created_at' => now()],
            ['name' => 'Grade 9', 'created_at' => now()],
            ['name' => 'Grade 10', 'created_at' => now()],
            ['name' => 'Grade 11', 'created_at' => now()],
            ['name' => 'After O/L', 'created_at' => now()],
            ['name' => 'Grade 12', 'created_at' => now()],
            ['name' => 'Grade 13', 'created_at' => now()],
            ['name' => 'After A/L', 'created_at' => now()],
        ]);

        // for subject_streams table
        DB::table('subject_streams')->insert([
            ['stream_name' => 'Physical Science', 'created_at' => now()],
            ['stream_name' => 'Biological Science', 'created_at' => now()],
            ['stream_name' => 'Engineering Technology', 'created_at' => now()],
            ['stream_name' => 'Bio System Technology', 'created_at' => now()],
            ['stream_name' => 'Commerce', 'created_at' => now()],
            ['stream_name' => 'Arts', 'created_at' => now()],
            ['stream_name' => 'Common', 'created_at' => now()],
            ['stream_name' => 'Other', 'created_at' => now()],
        ]);
    }
}

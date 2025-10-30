<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if users already exist
        if (DB::table('users')->whereIn('role_id', [1,2,3])->exists()) {
            $this->command->info('Admin, Teacher, or Student users already exist. Skipping.');
            return;
        }

        DB::table('users')->insert([
            [
                'name' => 'Admin User',
                'email' => 'test@admin.com',
                'user_id' => 'admin001', // login ID
                'password' => Hash::make('123456'),
                'role_id' => 1,
                'is_active' => 1,
                'email_verified_at' => now(),
                'remember_token' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Teacher User',
                'email' => 'test@teacher.com',
                'user_id' => 'teacher001', // login ID
                'password' => Hash::make('123456'),
                'role_id' => 2,
                'is_active' => 1,
                'email_verified_at' => now(),
                'remember_token' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Student User',
                'email' => 'test@student.com',
                'user_id' => '2025-0001', // YYYY-NNNN format
                'password' => Hash::make('123456'),
                'role_id' => 3,
                'is_active' => 1,
                'email_verified_at' => now(),
                'remember_token' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        $this->command->info('Admin, Teacher, and Student users created successfully.');
    }
}

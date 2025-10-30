<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Check if admin already exists
        if (DB::table('users')->where('role_id', 1)->exists()) {
            $this->command->info('Admin user already exists. Skipping.');
            return;
        }

        DB::table('users')->insert([
            [
                'user_id' => 'admin001',        // login ID
                'email' => 'test@admin.com',
                'password' => Hash::make('123456'),
                'role_id' => 1,
                'is_active' => 1,
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 'teacher001',      // login ID
                'email' => 'test@teacher.com',
                'password' => Hash::make('123456'),
                'role_id' => 2,
                'is_active' => 1,
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => '2025-0001',       // YYYY-NNNN format
                'email' => 'test@student.com',
                'password' => Hash::make('123456'),
                'role_id' => 3,
                'is_active' => 1,
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        $this->command->info('Admin, Teacher, and Student users created successfully.');
    }
}

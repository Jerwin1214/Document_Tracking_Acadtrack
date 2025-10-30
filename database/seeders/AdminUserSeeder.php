<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if admin already exists
        $existing = DB::table('users')->where('role_id', 1)->first();
        if ($existing) {
            $this->command->info('Admin user already exists. Skipping.');
            return;
        }

        // Insert admin user
DB::table('users')->insert([
    [
        'name' => 'Admin Test',
        'email' => 'test@admin.com',
        'password' => Hash::make('123456'),
        'role_id' => 1,
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'name' => 'Teacher Test',
        'email' => 'test@teacher.com',
        'password' => Hash::make('123456'),
        'role_id' => 2,
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'name' => 'Student Test',
        'email' => 'test@student.com',
        'password' => Hash::make('123456'),
        'role_id' => 3,
        'created_at' => now(),
        'updated_at' => now(),
    ],
]);


        $this->command->info('Admin user created: user_id = admin001, password = Admin@123');
    }
}

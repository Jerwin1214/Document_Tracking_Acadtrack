<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UserRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // --- 1️⃣ Clear roles table first ---
        DB::table('user_roles')->delete(); // safer than truncate

        // --- 2️⃣ Insert default roles ---
        DB::table('user_roles')->insert([
            [
                'id' => 1,
                'name' => 'Admin',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 2,
                'name' => 'Student',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 3,
                'name' => 'Teacher',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);

        // --- 3️⃣ Clear users table first (optional but safe for fresh seed) ---
        DB::table('users')->delete();

        // --- 4️⃣ Insert default users for each role ---
        DB::table('users')->insert([
            [
                'name' => 'Admin User',
                'user_id' => 'admin001',
                'email' => 'test@admin.com',
                'password' => Hash::make('password'),
                'role_id' => 1,
                'email_verified_at' => Carbon::now(),
                'remember_token' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Student User',
                'user_id' => 'student001',
                'email' => 'test@student.com',
                'password' => Hash::make('password'),
                'role_id' => 2,
                'email_verified_at' => Carbon::now(),
                'remember_token' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Teacher User',
                'user_id' => 'teacher001',
                'email' => 'test@teacher.com',
                'password' => Hash::make('password'),
                'role_id' => 3,
                'email_verified_at' => Carbon::now(),
                'remember_token' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}

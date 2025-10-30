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
            'user_id' => 'admin001', // This is what you will use to log in
            'password' => Hash::make('Admin@123'), // Default password
            'role_id' => 1, // 1 = admin
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        $this->command->info('Admin user created: user_id = admin001, password = Admin@123');
    }
}

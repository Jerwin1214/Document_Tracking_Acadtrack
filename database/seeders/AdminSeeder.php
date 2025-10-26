<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admins = [
            [
                'name'              => 'Admin001',
                'user_id'           => 'admin001',
                'email'             => 'erwinarellano53@gmail.com',
                'role_id'           => 1, // Admin role
                'remember_token'    => null,
                'email_verified_at' => now(),
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now(),
            ],
            [
                'name'              => 'Testing Laravel',
                'user_id'           => 'testinglaravel001',
                'email'             => 'testinglaravel001@gmail.com',
                'role_id'           => 1, // Admin role
                'remember_token'    => null,
                'email_verified_at' => now(),
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now(),
            ],
        ];

        foreach ($admins as $admin) {
            $existing = DB::table('users')->where('user_id', $admin['user_id'])->first();
            if ($existing) {
                $this->command->info("Admin already exists: {$admin['user_id']}. Skipping.");
                continue;
            }
            DB::table('users')->insert($admin);
            $this->command->info("✅ Admin created: {$admin['user_id']}");
        }
    }
}

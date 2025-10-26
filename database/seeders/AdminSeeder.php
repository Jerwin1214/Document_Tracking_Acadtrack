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
                'name'        => 'Admin001',
                'email'       => 'erwinarellano53@gmail.com',
                'password'    => Hash::make('Admin@123'),
                'user_role_id'=> 1, // Admin role
                'created_at'  => Carbon::now(),
                'updated_at'  => Carbon::now(),
            ],
            [
                'name'        => 'Testing Laravel',
                'email'       => 'testinglaravel001@gmail.com',
                'password'    => Hash::make('csulalloproject'),
                'user_role_id'=> 1, // Admin role
                'created_at'  => Carbon::now(),
                'updated_at'  => Carbon::now(),
            ],
        ];

        foreach ($admins as $admin) {
            $existing = DB::table('admins')->where('email', $admin['email'])->first();
            if ($existing) {
                $this->command->info("Admin already exists: {$admin['email']}. Skipping.");
                continue;
            }
            DB::table('admins')->insert($admin);
            $this->command->info("✅ Admin created: {$admin['email']}");
        }
    }
}

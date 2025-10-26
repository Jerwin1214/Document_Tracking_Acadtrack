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
        $admins = [
            [
                'user_id'    => 'admin001',
                'password'   => Hash::make('Admin@123'),
                'role_id'    => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'user_id'    => 'testinglaravel001',
                'password'   => Hash::make('csulalloproject'),
                'role_id'    => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        foreach ($admins as $admin) {
            $existing = DB::table('users')->where('user_id', $admin['user_id'])->first();
            if ($existing) {
                $this->command->info("Admin user {$admin['user_id']} already exists. Skipping.");
                continue;
            }
            DB::table('users')->insert($admin);
            $this->command->info("✅ Admin user created: user_id = {$admin['user_id']}");
        }
    }
}

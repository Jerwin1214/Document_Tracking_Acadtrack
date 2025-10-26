<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Optional: Clear the table before seeding (only if it's safe to do so)
        DB::table('user_roles')->delete(); // Use delete() instead of truncate() if foreign keys exist

        // Insert default user roles
        DB::table('user_roles')->insert([
            [
                'id' => 1,
                'name' => 'Admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'name' => 'Student',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'name' => 'Teacher',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}

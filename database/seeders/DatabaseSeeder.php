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


        // Call role seeder only
        $this->call([
        UserRolesSeeder::class,
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

        $this->call([
    AdminSeeder::class,
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


}
}

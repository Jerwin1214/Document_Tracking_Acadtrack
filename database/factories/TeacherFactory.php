<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Teacher>
 */
class TeacherFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'salutation' => $this->faker->title,
            'initials' => $this->faker->firstName,
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'nic' => $this->faker->regexify('[0-9]{9}[V]'),
            'dob' => $this->faker->date,
            'user_id' => User::factory(),
            'created_at' => now(),
        ];
    }
}

<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Crop>
 */
class CropFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array {
        return [
            'name' => $this->faker->word(),
            'type' => $this->faker->randomElement(['Fruit', 'Vegetable', 'Grain']),
            'status' => 'pending',
            'user_id' => User::inRandomOrder()->first()->id,
        ];
    }
}

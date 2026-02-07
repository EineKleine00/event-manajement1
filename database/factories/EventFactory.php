<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    // database/factories/EventFactory.php
    public function definition(): array
    {
        return [
            'name' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(),
            'date' => now()->addDays(rand(1, 30))->format('Y-m-d'), // Kolom date
            'location' => $this->faker->city(),
            'created_by' => \App\Models\User::factory(), // Kolom created_by
        ];
    }
}

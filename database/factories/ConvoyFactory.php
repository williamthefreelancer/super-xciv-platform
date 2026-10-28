<?php

namespace Database\Factories;

use App\Models\Convoy;
use App\Models\TravelRoute;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Convoy>
 */
class ConvoyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'host_id' => User::factory(),
            'travel_route_id' => TravelRoute::factory(),
            'name' => fake()->unique()->words(3, true),
            'slug' => fn (array $attributes) => Str::slug($attributes['name']).'-'.Str::lower(Str::random(5)),
            'notes' => fake()->sentence(14),
            'departs_at' => fake()->dateTimeBetween('+3 days', '+2 months'),
            'capacity' => fake()->numberBetween(4, 20),
        ];
    }

    public function configure(): static
    {
        return $this->afterCreating(function (Convoy $convoy): void {
            $convoy->members()->syncWithoutDetaching([$convoy->host_id]);
        });
    }
}

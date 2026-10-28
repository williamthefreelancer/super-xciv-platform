<?php

namespace Database\Factories;

use App\Models\TravelRoute;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<TravelRoute>
 */
class TravelRouteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $waypoints = [
            ['lat' => 51.5074, 'lng' => -0.1278, 'label' => 'Start'],
            ['lat' => 51.7520, 'lng' => -1.2577, 'label' => 'Coffee stop'],
            ['lat' => 51.3811, 'lng' => -2.3590, 'label' => 'Finish'],
        ];

        return [
            'owner_id' => User::factory(),
            'name' => fake()->unique()->words(3, true),
            'slug' => fn (array $attributes) => Str::slug($attributes['name']).'-'.Str::lower(Str::random(5)),
            'description' => fake()->sentence(12),
            'waypoints' => $waypoints,
            'distance_km' => TravelRoute::distanceFor($waypoints),
        ];
    }
}

<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Event>
 */
class EventFactory extends Factory
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
            'title' => fake()->unique()->sentence(3),
            'slug' => fn (array $attributes) => Str::slug($attributes['title']).'-'.Str::lower(Str::random(5)),
            'summary' => fake()->sentence(12),
            'description' => fake()->paragraphs(2, true),
            'venue' => fake()->company(),
            'city' => fake()->city(),
            'starts_at' => fake()->dateTimeBetween('+3 days', '+2 months'),
            'capacity' => fake()->numberBetween(8, 40),
        ];
    }

    public function past(): static
    {
        return $this->state(fn (array $attributes) => [
            'starts_at' => fake()->dateTimeBetween('-2 months', '-1 day'),
        ]);
    }
}

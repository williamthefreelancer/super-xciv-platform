<?php

namespace Database\Factories;

use App\Models\Broadcast;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Broadcast>
 */
class BroadcastFactory extends Factory
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
            'title' => fake()->unique()->words(3, true),
            'slug' => fn (array $attributes) => Str::slug($attributes['title']).'-'.Str::lower(Str::random(5)),
            'description' => fake()->sentence(14),
            'type' => 'video',
            'stream_url' => 'https://streams.example.com/live/stream.m3u8',
            'status' => Broadcast::STATUS_SCHEDULED,
            'scheduled_at' => fake()->dateTimeBetween('+1 day', '+1 month'),
        ];
    }

    public function audio(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'audio',
            'stream_url' => 'https://streams.example.com/live/radio.mp3',
        ]);
    }

    public function live(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => Broadcast::STATUS_LIVE,
            'started_at' => now()->subMinutes(10),
        ]);
    }

    public function ended(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => Broadcast::STATUS_ENDED,
            'started_at' => now()->subHours(2),
            'ended_at' => now()->subHour(),
        ]);
    }
}

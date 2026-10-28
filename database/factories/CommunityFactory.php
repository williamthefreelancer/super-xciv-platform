<?php

namespace Database\Factories;

use App\Models\Community;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Community>
 */
class CommunityFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'owner_id' => User::factory(),
            'name' => fake()->unique()->words(3, true),
            'slug' => fn (array $attributes) => Str::slug($attributes['name']).'-'.Str::lower(Str::random(5)),
            'summary' => fake()->sentence(10),
            'description' => fake()->paragraph(),
            'city' => fake()->city(),
        ];
    }

    public function configure(): static
    {
        return $this->afterCreating(function (Community $community): void {
            $community->members()->syncWithoutDetaching([$community->owner_id]);
        });
    }
}

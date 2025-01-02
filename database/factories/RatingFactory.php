<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Liamtseva\Cinema\Models\Movie;
use Liamtseva\Cinema\Models\Rating;
use Liamtseva\Cinema\Models\User;

/**
 * @extends Factory<Rating>
 */
class RatingFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'movie_id' => Movie::factory(),
            'number' => $this->faker->numberBetween(1, 10),
            'review' => $this->faker->optional()->text(200),
        ];
    }
}

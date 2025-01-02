<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Liamtseva\Cinema\Models\SearchHistory;
use Liamtseva\Cinema\Models\User;

/**
 * @extends Factory<SearchHistory>
 */
class SearchHistoryFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::inRandomOrder()->value('id'),
            'query' => $this->faker->word(),
        ];
    }

    public function forUserWithCount(int $count, $user = null): self
    {
        return $this->state(function (array $attributes) use ($user) {
            return [
                'user_id' => $user->id,
            ];
        })->count($count);
    }
}

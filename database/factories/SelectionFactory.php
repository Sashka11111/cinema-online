<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Liamtseva\Cinema\Models\Selection;
use Liamtseva\Cinema\Models\User;

/**
 * @extends Factory<Selection>
 */
class SelectionFactory extends Factory
{
    public function definition(): array
    {
        $name = $this->faker->sentence;

        return [
            'user_id' => User::inRandomOrder()->value('id'),  // Вибір випадкового користувача
            'slug' => $name,
            'name' => $name,
            'description' => $this->faker->optional()->paragraph,
            'meta_title' => $this->faker->optional()->sentence,
            'meta_description' => $this->faker->optional()->text(376),
            'meta_image' => $this->faker->imageUrl(2048, 2048),
        ];
    }
}

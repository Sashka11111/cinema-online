<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Liamtseva\Cinema\Models\Tag;

/**
 * @extends Factory<Tag>
 */
class TagFactory extends Factory
{
    public function definition(): array
    {
        $name = $this->faker->word();
        $slug = Str::slug($name).'-'.Str::random(6);

        return [
            'slug' => $slug,
            'name' => $name,
            'description' => $this->faker->sentence(10),
            'image' => $this->faker->boolean(50) ? $this->faker->imageUrl(640, 480, 'tags') : null,
            'aliases' => $this->faker->words(rand(0, 10)),
            'is_genre' => $this->faker->boolean(20),
            'meta_description' => $this->faker->boolean(70) ? $this->faker->text(376) : $this->faker->sentence(10),
            'meta_image' => $this->faker->boolean(50) ? $this->faker->imageUrl(640, 480, 'tags-meta') : null,
        ];
    }
}

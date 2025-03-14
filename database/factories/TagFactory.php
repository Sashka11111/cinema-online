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
        $name = $this->faker->word().' '.Str::substr(Str::ulid(), 0, 6);

        return [
            'slug' => Str::slug($name).'-'.Str::random(5),
            'name' => $name,
            'description' => $this->faker->sentence(10),
            'image' => $this->faker->boolean(50) ? $this->faker->imageUrl(640, 480, 'tags') : null,
            'aliases' => $this->faker->words(rand(0, 10)),
            'is_genre' => $this->faker->boolean(20),
            'meta_title' => $this->faker->boolean(70) ? $this->faker->words(3, true).'| '.config('app.name') : "$name | ".config('app.name'),
            'meta_description' => $this->faker->boolean(70) ? $this->faker->sentence(15) : $this->faker->sentence(10),
            'meta_image' => $this->faker->boolean(50) ? $this->faker->imageUrl(640, 480, 'tags-meta') : null,
        ];
    }
}

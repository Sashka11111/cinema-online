<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class StudioFactory extends Factory
{
    public function definition(): array
    {
        $company = $this->faker->unique()->company();
        $slug = Str::slug($company).'-'.Str::random(6);

        return [
            'slug' => $slug,
            'name' => $company,
            'description' => $this->faker->paragraph(),
            'image' => $this->faker->imageUrl(),
            'meta_title' => $this->faker->sentence(),
            'meta_description' => $this->faker->optional()->text(376),
            'meta_image' => $this->faker->imageUrl(),
        ];
    }
}

<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Liamtseva\Cinema\Enums\Gender;
use Liamtseva\Cinema\Enums\PersonType;
use Liamtseva\Cinema\Models\Person;

/**
 * @extends Factory<Person>
 */
class PersonFactory extends Factory
{
    public function definition(): array
    {
        $name = $this->faker->name();

        return [
            'slug' => $name,
            'name' => $name,
            'original_name' => $this->faker->optional()->name(),
            'gender' => $this->faker->randomElement(Gender::cases())->value,
            'image' => $this->faker->imageUrl(640, 480, 'people'),
            'description' => $this->faker->sentence(15),
            'birthday' => $this->faker->optional()->date(),
            'birthplace' => $this->faker->optional()->city(),
            'meta_title' => $this->faker->randomElement(PersonType::cases())->name().' '.$name.' | '.config('app.name'),
            'meta_description' => $this->faker->sentence(15),
            'meta_image' => $this->faker->imageUrl(640, 480, 'people-meta', true),
            'type' => $this->faker->randomElement(PersonType::cases())->value,
        ];
    }
}

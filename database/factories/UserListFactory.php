<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Liamtseva\Cinema\Enums\UserListType;
use Liamtseva\Cinema\Models\Episode;
use Liamtseva\Cinema\Models\Movie;
use Liamtseva\Cinema\Models\Person;
use Liamtseva\Cinema\Models\Selection;
use Liamtseva\Cinema\Models\Tag;
use Liamtseva\Cinema\Models\User;
use Liamtseva\Cinema\Models\UserList;

/**
 * @extends Factory<UserList>
 */
class UserListFactory extends Factory
{
    public function definition(): array
    {
        // Список доступних класів для `listable_type`
        $listableClasses = [
            Movie::class,
            Episode::class,
            Person::class,
            Tag::class,
            Selection::class,
        ];

        // Випадковий вибір класу
        $listableClass = $this->faker->randomElement($listableClasses);

        // Створення або вибір випадкового запису відповідного класу
        $listable = $listableClass::inRandomOrder()->first()
            ?? $listableClass::factory()->create();

        return [
            'user_id' => User::inRandomOrder()->value('id'),
            'listable_id' => $listable->id,
            'listable_type' => $listableClass,
            'type' => $this->faker->randomElement(UserListType::cases())->value,
        ];
    }
}

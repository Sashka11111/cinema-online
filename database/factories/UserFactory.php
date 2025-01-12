<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Liamtseva\Cinema\Enums\Gender;
use Liamtseva\Cinema\Enums\Role;
use Liamtseva\Cinema\Models\User;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    protected static ?string $password = 'Qwerty123$';

    public function definition(): array
    {
        return [
            'name' => fake()->unique()->userName(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => fake()->randomElement([now(), null]),
            'password' => static::$password ??= Hash::make('password'), // Кешування пароля
            'remember_token' => Str::random(10),
            'role' => fake()->randomElement([Role::USER->value, Role::ADMIN->value]),
            'avatar' => fake()->imageUrl(200, 200, 'people', true, 'avatar'),
            'backdrop' => fake()->imageUrl(800, 400, 'nature', true, 'backdrop'),
            'gender' => fake()->randomElement([Gender::MALE->value, Gender::FEMALE->value, null]),
            'description' => fake()->sentence(10),
            'birthday' => fake()->dateTimeBetween('-70 years', '-18 years')->format('Y-m-d'),
            'allow_adult' => fake()->boolean(75),
            'last_seen_at' => fake()->dateTimeThisMonth(),
            'is_auto_next' => fake()->boolean(75),
            'is_auto_play' => fake()->boolean(50),
            'is_auto_skip_intro' => fake()->boolean(60),
            'is_private_favorites' => fake()->boolean(40),
        ];
    }

    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => Role::ADMIN->value,
        ]);
    }

    public function gender(Gender $gender): static
    {
        return $this->state(fn (array $attributes) => [
            'gender' => $gender->value,
        ]);
    }
}

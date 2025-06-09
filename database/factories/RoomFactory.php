<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Liamtseva\Cinema\Enums\RoomStatus;
use Liamtseva\Cinema\Models\Episode;
use Liamtseva\Cinema\Models\Room;
use Liamtseva\Cinema\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Liamtseva\Cinema\Models\Room>
 */
class RoomFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Room::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->sentence(3);
        $isPrivate = $this->faker->boolean(20); // 20% шанс, що кімната приватна

        return [
            'name' => $name,
            'slug' => Str::slug($name).'-'.Str::random(6),
            'user_id' => User::factory(),
            'episode_id' => Episode::factory(),
            'is_private' => $isPrivate,
            'room_status' => RoomStatus::NOT_STARTED,
            'password' => $isPrivate ? bcrypt('password') : null,
            'max_viewers' => $this->faker->numberBetween(5, 10),
            'started_at' => null,
            'ended_at' => null,
        ];
    }

    /**
     * Вказати, що кімната активна.
     */
    public function active(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'room_status' => RoomStatus::ACTIVE,
                'started_at' => now()->subMinutes($this->faker->numberBetween(5, 120)),
                'ended_at' => null,
            ];
        });
    }

    /**
     * Вказати, що кімната завершена.
     */
    public function completed(): self
    {
        return $this->state(function (array $attributes) {
            $startedAt = now()->subHours($this->faker->numberBetween(1, 24));

            return [
                'room_status' => RoomStatus::COMPLETED,
                'started_at' => $startedAt,
                'ended_at' => $startedAt->copy()->addMinutes($this->faker->numberBetween(30, 180)),
            ];
        });
    }

    /**
     * Вказати, що кімната ще не розпочата.
     */
    public function notStarted(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'room_status' => RoomStatus::NOT_STARTED,
                'started_at' => null,
                'ended_at' => null,
            ];
        });
    }

    /**
     * Вказати, що кімната публічна.
     */
    public function public(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'is_private' => false,
                'password' => null,
            ];
        });
    }

    /**
     * Вказати, що кімната приватна.
     */
    public function private(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'is_private' => true,
                'password' => bcrypt('password'),
            ];
        });
    }
}

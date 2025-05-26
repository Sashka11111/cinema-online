<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Liamtseva\Cinema\Enums\RoomStatus;
use Liamtseva\Cinema\Models\Episode;
use Liamtseva\Cinema\Models\Room;
use Liamtseva\Cinema\Models\User;

class RoomSeeder extends Seeder
{
    /**
     * Заповнює базу даних тестовими кімнатами.
     */
    public function run(): void
    {
        // Отримуємо користувачів та епізоди для створення кімнат
        $users = User::all();
        $episodes = Episode::all();

        if ($users->isEmpty() || $episodes->isEmpty()) {
            $this->command->warn('Немає користувачів або епізодів для створення кімнат. Спочатку запустіть UserSeeder та EpisodeSeeder.');

            return;
        }

        // Створюємо 50 тестових кімнат
        $rooms = [];
        for ($i = 0; $i < 50; $i++) {
            $user = $users->random();
            $episode = $episodes->random();
            $isPrivate = rand(0, 1) === 1;
            $maxViewers = rand(5, 20);

            // Визначаємо статус кімнати
            $status = RoomStatus::cases()[array_rand(RoomStatus::cases())];

            // Встановлюємо дати відповідно до статусу
            $startedAt = null;
            $endedAt = null;

            if ($status === RoomStatus::ACTIVE) {
                $startedAt = now()->subHours(rand(1, 24));
            } elseif ($status === RoomStatus::COMPLETED) {
                $startedAt = now()->subDays(rand(1, 30));
                $endedAt = now()->subHours(rand(1, 12));
            }

            $room = Room::create([
                'name' => "Кімната {$user->name} - {$episode->name}",
                'slug' => "room-{$i}-".Str::random(6),
                'user_id' => $user->id,
                'episode_id' => $episode->id,
                'room_status' => $status,
                'is_private' => $isPrivate,
                'password' => $isPrivate ? bcrypt('password') : null,
                'max_viewers' => $maxViewers,
                'started_at' => $startedAt,
                'ended_at' => $endedAt,
            ]);

            $rooms[] = $room;

            // Додаємо глядачів до кімнат
            $viewersCount = rand(1, min($maxViewers - 1, 5));
            $viewers = $users->random($viewersCount);

            foreach ($viewers as $viewer) {
                // Пропускаємо власника кімнати
                if ($viewer->id === $user->id) {
                    continue;
                }

                // Визначаємо час приєднання та виходу залежно від статусу кімнати
                $joinedAt = null;
                $leftAt = null;

                if ($status === RoomStatus::ACTIVE) {
                    $joinedAt = now()->subMinutes(rand(5, 120));
                    // 50% шанс, що глядач все ще в кімнаті
                    $leftAt = rand(0, 1) === 1 ? now()->subMinutes(rand(1, 5)) : null;
                } elseif ($status === RoomStatus::COMPLETED && $startedAt && $endedAt) {
                    $joinedAt = (clone $startedAt)->addMinutes(rand(1, 30));
                    $leftAt = (clone $endedAt)->subMinutes(rand(1, 30));

                    // Переконуємося, що час виходу після часу входу
                    if ($leftAt <= $joinedAt) {
                        $leftAt = (clone $joinedAt)->addMinutes(rand(5, 60));
                    }
                }

                if ($joinedAt) {
                    $room->viewers()->attach($viewer->id, [
                        'joined_at' => $joinedAt,
                        'left_at' => $leftAt,
                    ]);
                }
            }
        }

        $this->command->info('Створено '.count($rooms).' тестових кімнат з глядачами.');
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Liamtseva\Cinema\Models\SearchHistory;
use Liamtseva\Cinema\Models\User;

class SearchHistorySeeder extends Seeder
{
    public function run(): void
    {
        // Отримуємо всіх користувачів
        $users = User::all();

        // Вибираємо 80% користувачів
        $usersToSeed = $users->random(floor($users->count() * 0.8));

        // Для кожного з вибраних користувачів генеруємо кілька історій пошуку
        foreach ($usersToSeed as $user) {
            // Визначаємо випадкову кількість історій пошуку (наприклад, від 5 до 15)
            $searchHistoryCount = rand(5, 15);

            for ($i = 0; $i < $searchHistoryCount; $i++) {
                // Створюємо запис в історії пошуку для цього користувача
                SearchHistory::factory()->create([
                    'user_id' => $user->id,
                ]);
            }
        }
    }
}

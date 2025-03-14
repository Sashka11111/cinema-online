<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Liamtseva\Cinema\Models\Movie;
use Liamtseva\Cinema\Models\Person;
use Liamtseva\Cinema\Models\Selection;

class SelectionSeeder extends Seeder
{
    public function run(): void
    {
        // Отримуємо всі ID фільмів і персон
        $movieIds = Movie::pluck('id')->toArray();
        $personIds = Person::pluck('id')->toArray();

        if (empty($movieIds) || empty($personIds)) {
            $this->command->warn('Недостатньо фільмів або персон для підбірок.');

            return;
        }

        // Створюємо 20 підбірок
        $selections = Selection::factory(20)->create();

        // Додаємо фільми та персони до кожної підбірки
        $selections->each(function (Selection $selection) use ($movieIds, $personIds) {
            // Вибираємо унікальні фільми та персони
            $movies = collect($movieIds)->shuffle()->take(rand(5, 10));
            $persons = collect($personIds)->shuffle()->take(rand(5, 10));

            $selection->movies()->syncWithoutDetaching($movies);
            $selection->persons()->syncWithoutDetaching($persons);
        });
    }
}

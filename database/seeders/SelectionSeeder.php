<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Liamtseva\Cinema\Models\Movie;
use Liamtseva\Cinema\Models\Person;
use Liamtseva\Cinema\Models\Selection;

// TODO: відрефакторити
class SelectionSeeder extends Seeder
{
    public function run(): void
    {
        // Створюємо кілька selection
        $selections = Selection::factory(20)->create();

        // Додаємо фільми та персони до кожної підбірки
        $selections->each(function (Selection $selection) {
            // Вибираємо унікальні фільми та персони для підбірки
            $movies = Movie::inRandomOrder()->take(rand(5, 10))->pluck('id');
            $persons = Person::inRandomOrder()->take(rand(5, 10))->pluck('id');

            $selection->movies()->attach($movies);
            $selection->persons()->attach($persons);
        });
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Liamtseva\Cinema\Enums\UserListType;
use Liamtseva\Cinema\Models\Episode;
use Liamtseva\Cinema\Models\Movie;
use Liamtseva\Cinema\Models\Person;
use Liamtseva\Cinema\Models\Tag;
use Liamtseva\Cinema\Models\User;

class UserListSeeder extends Seeder
{
    public function run(): void
    {
        // Всі користувачі
        $users = User::all();

        foreach ($users as $user) {
            // Улюблені фільми
            $favoriteMovies = Movie::inRandomOrder()->take(rand(5, 15))->get();
            foreach ($favoriteMovies as $movie) {
                $user->userLists()->create([
                    'listable_id' => $movie->id,
                    'listable_type' => Movie::class,
                    'type' => UserListType::FAVORITE->value,
                ]);
            }

            // Улюблені персони
            $favoritePeople = Person::inRandomOrder()->take(rand(5, 15))->get();
            foreach ($favoritePeople as $person) {
                $user->userLists()->create([
                    'listable_id' => $person->id,
                    'listable_type' => Person::class,
                    'type' => UserListType::FAVORITE->value,
                ]);
            }

            // Улюблені теги
            $favoriteTags = Tag::inRandomOrder()->take(rand(5, 15))->get();
            foreach ($favoriteTags as $tag) {
                $user->userLists()->create([
                    'listable_id' => $tag->id,
                    'listable_type' => Tag::class,
                    'type' => UserListType::FAVORITE->value,
                ]);
            }

            // Переглядає епізоди
            $watchingEpisodes = Episode::inRandomOrder()->take(rand(5, 15))->get();
            foreach ($watchingEpisodes as $episode) {
                $user->userLists()->create([
                    'listable_id' => $episode->id,
                    'listable_type' => Episode::class,
                    'type' => UserListType::WATCHING->value,
                ]);
            }

            // Заплановані фільми
            /*            $plannedMovies = Movie::inRandomOrder()->take(rand(5, 15))->get();
                        foreach ($plannedMovies as $movie) {
                            $user->userLists()->create([
                                'listable_id' => $movie->id,
                                'listable_type' => Movie::class,
                                'type' => UserListType::PLANNED->value,
                            ]);
                        }*/
        }
    }
}

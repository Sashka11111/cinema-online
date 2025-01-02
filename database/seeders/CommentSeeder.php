<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Liamtseva\Cinema\Models\Comment;
use Liamtseva\Cinema\Models\Episode;
use Liamtseva\Cinema\Models\Movie;
use Liamtseva\Cinema\Models\Selection;
use Liamtseva\Cinema\Models\User;

class CommentSeeder extends Seeder
{
    public function run(): void
    {
        // Отримуємо всі наявні фільми, епізоди, добірки та користувачів
        $movies = Movie::all();
        $episodes = Episode::all();
        $selections = Selection::all();
        $users = User::all();

        // Додаємо коментарі до фільмів
        foreach ($movies as $movie) {
            Comment::factory()
                ->forCommentable($movie)
                ->forUser($users->random())
                ->count(rand(1, 5)) // Випадкова кількість коментарів
                ->create();
        }

        // Додаємо коментарі до епізодів
        foreach ($episodes as $episode) {
            Comment::factory()
                ->forCommentable($episode)
                ->forUser($users->random())
                ->count(rand(1, 5)) // Випадкова кількість коментарів
                ->create();
        }

        // Додаємо коментарі до добірок
        foreach ($selections as $selection) {
            Comment::factory()
                ->forCommentable($selection)
                ->forUser($users->random())
                ->count(rand(1, 5)) // Випадкова кількість коментарів
                ->create();
        }

        // Додаємо вкладені коментарі до деяких з уже створених
        $parentComments = Comment::roots()->inRandomOrder()->take(10)->get();

        foreach ($parentComments as $parent) {
            Comment::factory()
                ->replyTo($parent)
                ->forUser($users->random())
                ->count(rand(1, 3)) // Випадкова кількість відповідей
                ->create();
        }
    }
}

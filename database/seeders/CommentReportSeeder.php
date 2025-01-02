<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Liamtseva\Cinema\Enums\CommentReportType;
use Liamtseva\Cinema\Models\Comment;
use Liamtseva\Cinema\Models\CommentReport;
use Liamtseva\Cinema\Models\User;

class CommentReportSeeder extends Seeder
{
    public function run(): void
    {
        $comments = Comment::inRandomOrder()->take(round(Comment::count() * 0.1))->get();
        $users = User::all();

        foreach ($comments as $comment) {
            $reportsCount = rand(1, 3);

            $randomUsers = $users->where('id', '!=', $comment->user_id)
                ->random(min($reportsCount, $users->count()));

            // Створюємо репорти для кожного вибраного користувача
            foreach ($randomUsers as $user) {
                CommentReport::factory()
                    ->forCommentAndUser($comment, $user)  // Призначаємо конкретний коментар та користувача
                    ->withType($this->getRandomReportType())  // Встановлюємо випадковий тип репорту
                    ->create();
            }
        }
    }

    /**
     * Повертає випадковий тип репорту
     */
    private function getRandomReportType(): CommentReportType
    {
        return Arr::random(CommentReportType::cases());
    }
}

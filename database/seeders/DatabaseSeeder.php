<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            TagSeeder::class,
            StudioSeeder::class,
            PersonSeeder::class,
            MovieSeeder::class,
            RatingSeeder::class,
            EpisodeSeeder::class,
            SelectionSeeder::class,
            UserListSeeder::class,
            CommentSeeder::class,
            CommentLikeSeeder::class,
            CommentReportSeeder::class,
            SelectionSeeder::class,
            SearchHistorySeeder::class,
            WatchHistorySeeder::class,
            RoomSeeder::class, // Додаємо сідер для кімнат
        ]);
    }
}

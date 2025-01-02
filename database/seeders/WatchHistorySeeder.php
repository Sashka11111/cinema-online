<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Liamtseva\Cinema\Models\Episode;
use Liamtseva\Cinema\Models\User;
use Liamtseva\Cinema\Models\WatchHistory;
use Ramsey\Collection\Collection;

class WatchHistorySeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();

        foreach ($users as $user) {
            $numberOfEpisodes = rand(0, 50);
            /** @var Collection<Episode> $episodes */
            $episodes = Episode::inRandomOrder()->take($numberOfEpisodes)->get();

            foreach ($episodes as $episode) {
                WatchHistory::create([
                    'user_id' => $user->id,
                    'episode_id' => $episode->id,
                    'progress_time' => rand(0, $episode->duration), // Випадковий прогрес в секундах
                ]);
            }
        }
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Liamtseva\Cinema\Enums\Kind;
use Liamtseva\Cinema\Models\Episode;
use Liamtseva\Cinema\Models\Movie;

class EpisodeSeeder extends Seeder
{
    public function run(): void
    {
        $movies = Movie::all();

        foreach ($movies as $movie) {
            if ($movie->kind === Kind::MOVIE) {
                // Для фільмів типу Movie завжди один епізод з номером 1
                Episode::factory()
                    ->forMovie($movie)
                    ->create(['number' => 1]);
            } elseif ($movie->kind === Kind::TV_SERIES) {
                $episodeCount = rand(2, 10);

                for ($i = 1; $i <= $episodeCount; $i++) {
                    $number = Episode::factory()->generateUniqueNumber($movie->id, false);

                    Episode::factory()
                        ->forMovie($movie)
                        ->create(['number' => $number]);
                }
            }
        }
    }
}

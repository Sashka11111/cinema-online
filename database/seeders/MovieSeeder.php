<?php

namespace Database\Seeders;

use Database\Factories\MovieFactory;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Liamtseva\Cinema\Enums\Kind;
use Liamtseva\Cinema\Enums\Period;
use Liamtseva\Cinema\Enums\RestrictedRating;
use Liamtseva\Cinema\Enums\Source;
use Liamtseva\Cinema\Models\Movie;
use Liamtseva\Cinema\Models\Person;
use Liamtseva\Cinema\Models\Studio;
use Liamtseva\Cinema\Models\Tag;
use Liamtseva\Cinema\Models\User;

class MovieSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        $movieFactory = new MovieFactory;

        // Отримуємо топ 100 фільмів/серіалів з TheMovieDB
        $moviesData = $this->getTopMoviesFromTMDB();

        $releaseDate = $movieData['release_date'] ?? $movieData['first_air_date'] ?? null;
        $period = $releaseDate ? Period::fromDate($releaseDate) : null;

        $firstAirDate = $movieFactory->parseValidDate($releaseDate)
            ?? $movieFactory->parseValidDate($releaseDate)
            ?? $faker->date();

        $lastAirDate = $movieFactory->parseValidDate($releaseDate)
            ?? $movieFactory->parseValidDate($releaseDate)
            ?? $faker->date();

        // Прив'язуємо фільми до користувачів
        $movies = collect($moviesData)->map(function ($movieData) use ($period, $movieFactory, $faker, $firstAirDate, $lastAirDate) {

            // Створюємо фільм
            return Movie::create([
                'api_sources' => $movieFactory->getApiSources($movieData),
                'slug' => $movieData['title'],
                'name' => $movieData['title'],
                'description' => $movieFactory->getDescription($movieData),
                'image_name' => $faker->imageUrl(200, 100, 'movies'),
                'aliases' => collect([$movieData['original_title'] ?? $faker->words(rand(0, 10))]),
                'studio_id' => Studio::query()->inRandomOrder()->value('id'),
                'kind' => Kind::MOVIE,
                'status' => $movieFactory->determineStatus($movieData)->value,
                'period' => $period?->value,
                'restricted_rating' => $faker->randomElement(RestrictedRating::cases())->value,
                'source' => $faker->randomElement(Source::cases())->value,
                'countries' => $movieFactory->getCountries($movieData),
                'poster' => $movieFactory->getPoster($movieData),
                'duration' => $movieData['runtime'] ?? 120,
                'episodes_count' => $movieFactory->getEpisodesCount($movieData),
                'first_air_date' => $firstAirDate,
                'last_air_date' => $lastAirDate,
                'imdb_score' => $movieData['vote_average'] ?? $faker->randomFloat(1, 1, 10),
                'attachments' => $movieFactory->generateAttachments(),
                'related' => [],
                'similars' => [],
                'is_published' => $faker->boolean(),
                'meta_title' => 'Дивитись онлайн '.$movieData['title'].' | '.config('app.name'),
                'meta_description' => $movieFactory->getDescription($movieData),
                'meta_image' => $movieFactory->getBackdrop($movieData),
            ]);
        });

        // Прив'язуємо фільми до користувачів
        User::all()->each(function ($user) use ($movies) {
            $user->movieNotifications()->attach(
                $movies->random(rand(1, 5))->pluck('id'),
                ['created_at' => now(), 'updated_at' => now()]
            );
        });

        // Отримуємо людей та теги
        $persons = Person::all();
        $tags = Tag::all();

        // Додаємо теги та людей до кожного фільму
        $movies->each(function ($movie) use ($persons, $tags) {
            // Додаємо від 5 до 20 випадкових тегів
            $randomTags = $tags->random(rand(5, 20));
            $movie->tags()->attach($randomTags->pluck('id'));

            // Додаємо випадкових людей (акторів)
            $randomPersons = $persons->random(rand(1, 5));
            foreach ($randomPersons as $person) {
                $movie->persons()->attach($person, [
                    'character_name' => fake()->name(),
                ]);
            }
        });
    }

    /**
     * Отримуємо топ 100 фільмів/серіалів з TheMovieDB API
     */
    private function getTopMoviesFromTMDB(): array
    {
        // Отримуємо топ 100 фільмів/серіалів
        $response = Http::get('https://api.themoviedb.org/3/discover/movie', [
            'api_key' => env('TMDB_API_KEY'),
            'language' => 'uk-UA',
            'sort_by' => 'popularity.desc', // Сортуємо за популярністю
            'page' => 1, // Потрібно отримати першу сторінку
        ]);

        return $response->successful() ? $response->json()['results'] : [];
    }
}

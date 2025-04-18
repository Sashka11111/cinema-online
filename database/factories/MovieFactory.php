<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Liamtseva\Cinema\Enums\ApiSourceName;
use Liamtseva\Cinema\Enums\AttachmentType;
use Liamtseva\Cinema\Enums\Country;
use Liamtseva\Cinema\Enums\Kind;
use Liamtseva\Cinema\Enums\MovieRelateType;
use Liamtseva\Cinema\Enums\Period;
use Liamtseva\Cinema\Enums\RestrictedRating;
use Liamtseva\Cinema\Enums\Source;
use Liamtseva\Cinema\Enums\Status;
use Liamtseva\Cinema\Models\Studio;
use Liamtseva\Cinema\ValueObjects\ApiSource;

class MovieFactory extends Factory
{
    public function definition(): array
    {
        $movieOrTv = $this->faker->boolean(75) ? 'movie' : 'tv';
        $movieData = $this->getMovieData($movieOrTv);

        $titleKey = $movieOrTv === 'movie' ? 'title' : 'name';
        $title = $movieData[$titleKey] ?? $this->faker->word;
        $originalTitle = $movieData["original_{$titleKey}"] ?? $this->faker->words(rand(0, 10));
        $kind = $movieOrTv === 'movie' ? Kind::MOVIE : Kind::TV_SERIES;

        $releaseDate = $movieData['release_date'] ?? $movieData['first_air_date'] ?? null;
        $period = $releaseDate ? Period::fromDate($releaseDate) : null;

        $firstAirDate = $this->parseValidDate($releaseDate)
            ?? $this->parseValidDate($releaseDate)
            ?? $this->faker->date();

        $lastAirDate = $this->parseValidDate($releaseDate)
            ?? $this->parseValidDate($releaseDate)
            ?? $this->faker->date();
        $slug = Str::slug($title).'-'.Str::random(6);

        return [
            'api_sources' => $this->getApiSources($movieData),
            'slug' => $slug,
            'name' => $title,
            'description' => $this->getDescription($movieData),
            'image_name' => $this->faker->imageUrl(200, 100, 'movies'),
            'aliases' => [$originalTitle],
            'studio_id' => Studio::query()->inRandomOrder()->value('id') ?? Studio::factory(),
            'kind' => $kind->value,
            'status' => $this->determineStatus($movieData)->value,
            'period' => $period?->value,
            'restricted_rating' => $this->faker->randomElement(RestrictedRating::cases())->value,
            'source' => $this->faker->randomElement(Source::cases())->value,
            'countries' => $this->getCountries($movieData),
            'poster' => $this->getPoster($movieData),
            'duration' => $this->getDuration($movieData),
            'episodes_count' => $this->getEpisodesCount($movieData),
            'first_air_date' => $firstAirDate,
            'last_air_date' => $lastAirDate,
            'imdb_score' => $movieData['vote_average'] ?? $this->faker->randomFloat(1, 1, 10),
            'attachments' => $this->generateAttachments(),
            'related' => $this->generateRelatedMovies(),
            'similars' => [],
            'is_published' => $this->faker->boolean(),
            'meta_title' => Str::limit("Дивитись онлайн $title | ".config('app.name'), 255, '...'),
            'meta_description' => $this->getDescription($movieData),
            'meta_image' => $this->getBackdrop($movieData),
        ];
    }

    private function getMovieData(string $movieOrTv): array
    {
        $randomId = $movieOrTv === 'movie' ? $this->faker->numberBetween(70_000, 90_000) : $this->faker->numberBetween(4_000, 5_000);

        $response = Http::get("https://api.themoviedb.org/3/{$movieOrTv}/{$randomId}", [
            'api_key' => env('TMDB_API_KEY'),
            'language' => 'uk-UA',
            'append_to_response' => 'tags, images, videos, production_countries',
        ]);

        return $response->successful() ? $response->json() : [];
    }

    public function parseValidDate($date)
    {
        return ! empty($date) && Carbon::parse($date)->isValid() ? Carbon::parse($date)->toDateString() : null;
    }

    public function getApiSources(array $movieData): array
    {
        return collect([
            'id' => ApiSourceName::TMDB,
            'imdb_id' => ApiSourceName::IMDB,
        ])
            ->filter(fn ($source, $key) => isset($movieData[$key]))
            ->map(fn ($source, $key) => new ApiSource($source, $movieData[$key]))
            ->values()
            ->toArray();
    }

    public function getDescription(array $movieData): string
    {
        return Str::limit(data_get($movieData, 'overview', $this->faker->sentence(15)), 450, '...');
    }

    public function determineStatus(array $movieData): Status
    {
        if (isset($movieData['status'])) {
            if ($movieData['status'] === 'Ended' || $movieData['status'] === 'Released') {
                return Status::RELEASED;
            }

            if ($movieData['status'] === 'Canceled') {
                return Status::CANCELED;
            }

            if ($movieData['in_production'] === true) {
                return Status::ONGOING;
            }

            return Status::from($movieData['status']);
        }

        return $this->faker->randomElement(Status::cases());
    }

    public function getCountries(array $movieData): array
    {
        $countries = $movieData['production_countries'] ?? [];

        return collect($countries)
            ->map(function ($country) {
                $isoCode = $country['iso_3166_1'] ?? 'US';

                return Country::tryFrom($isoCode) ?? $isoCode;
            })
            ->filter()
            ->values()
            ->toArray();
    }

    public function getPoster(array $movieData): string
    {
        return isset($movieData['poster_path']) ? "https://image.tmdb.org/t/p/w500{$movieData['poster_path']}" : $this->faker->imageUrl(800, 1200);
    }

    private function getDuration(array $movieData): int
    {
        return $movieData['runtime'] ?? $this->faker->numberBetween(60, 180);
    }

    public function getEpisodesCount(array $movieData): int
    {
        return $movieData['episode_count'] ?? 1;
    }

    public function generateAttachments(): array
    {
        return [
            ['type' => AttachmentType::PICTURE->value, 'url' => $this->faker->imageUrl()],
            ['type' => AttachmentType::TRAILER->value, 'url' => $this->faker->url()],
            ['type' => AttachmentType::CLIP->value, 'url' => $this->faker->url()],
        ];
    }

    private function generateRelatedMovies(): array
    {
        return [
            ['id' => $this->faker->randomNumber(), 'type' => MovieRelateType::SEASON->value],
            ['id' => $this->faker->randomNumber(), 'type' => MovieRelateType::SEQUEL->value],
            ['id' => $this->faker->randomNumber(), 'type' => MovieRelateType::PREQUEL->value],
        ];
    }

    public function getBackdrop(array $movieData): string
    {
        return isset($movieData['backdrop_path']) ? "https://image.tmdb.org/t/p/w500{$movieData['backdrop_path']}" : $this->faker->imageUrl(800, 1200);
    }
}

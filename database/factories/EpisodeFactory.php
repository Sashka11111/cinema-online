<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Collection;
use Liamtseva\Cinema\Enums\Kind;
use Liamtseva\Cinema\Enums\VideoPlayerName;
use Liamtseva\Cinema\Enums\VideoQuality;
use Liamtseva\Cinema\Models\Episode;
use Liamtseva\Cinema\Models\Movie;
use Liamtseva\Cinema\ValueObjects\VideoPlayer;

/**
 * @extends Factory<Episode>
 */
class EpisodeFactory extends Factory
{
    public function definition(): array
    {
        $movie = Movie::query()->inRandomOrder()->first();
        $isMovieKind = $movie->kind === Kind::MOVIE; // Перевірка типу Movie

        $name = $this->faker->sentence(3);

        return [
            'movie_id' => $movie->id,
            'number' => $this->generateUniqueNumber($movie->id, $isMovieKind),
            'slug' => $name,
            'name' => $name,
            'description' => $this->faker->paragraph(),
            'duration' => $this->faker->numberBetween(20, 120), // Duration in minutes
            'air_date' => $this->faker->optional()->dateTimeBetween('-2 years', 'now'),
            'is_filler' => $this->faker->boolean(10), // 10% chance of being filler
            'pictures' => json_encode($this->generatePictureUrls(rand(1, 3))),
            'video_players' => $this->generateVideoPlayers(),
            'meta_title' => $this->faker->optional()->sentence(5),
            'meta_description' => $this->faker->optional()->sentence(10),
            'meta_image' => $this->faker->optional()->imageUrl(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function generateUniqueNumber(string $movieId, bool $isMovieKind): int
    {
        if ($isMovieKind) {
            return 1; // Для фільмів завжди номер 1
        }

        // Отримуємо всі існуючі номери для конкретного фільму
        $existingNumbers = collect(Episode::where('movie_id', $movieId)->pluck('number'));

        // Знаходимо наступний доступний номер
        $newNumber = 1;
        while ($existingNumbers->contains($newNumber)) {
            $newNumber++;
        }

        return $newNumber;
    }

    private function generatePictureUrls(int $count): array
    {
        return $this->faker->randomElements([
            $this->faker->imageUrl(640, 480, 'movies', true, 'Episode 1'),
            $this->faker->imageUrl(640, 480, 'movies', true, 'Episode 2'),
            $this->faker->imageUrl(640, 480, 'movies', true, 'Episode 3'),
        ], $count);
    }

    /**
     * @return Collection<VideoPlayer>
     */
    private function generateVideoPlayers(): Collection
    {
        $videoPlayers = collect([
            [
                'name' => VideoPlayerName::KODIK,
                'url' => $this->faker->url(),
                'file_url' => $this->faker->url(),
                'dubbing' => $this->faker->boolean(50) ? 'uk' : 'en',
                'quality' => VideoQuality::HD,
                'locale_code' => $this->faker->randomElement(['uk', 'en']),
            ],
            [
                'name' => VideoPlayerName::ALOHA,
                'url' => $this->faker->url(),
                'file_url' => $this->faker->url(),
                'dubbing' => $this->faker->boolean(50) ? 'uk' : 'en',
                'quality' => VideoQuality::FULL_HD,
                'locale_code' => $this->faker->randomElement(['uk', 'en']),
            ],
        ]);

        return $videoPlayers->map(fn ($data) => new VideoPlayer(
            $data['name'],
            $data['url'],
            $data['file_url'],
            $data['dubbing'],
            $data['quality'],
            $data['locale_code']
        ));
    }

    public function forMovie(Movie $movie): self
    {
        return $this->state(fn () => [
            'movie_id' => $movie->id,
        ]);
    }
}

<?php

namespace Liamtseva\Cinema\Models\Builders;

use Illuminate\Database\Eloquent\Builder;

class WatchHistoryQueryBuilder extends Builder
{
    /**
     * Filter watch history by user.
     *
     * @param string $userId
     * @return $this
     */
    public function forUser(string $userId): self
    {
        return $this->where('user_id', $userId);
    }

    /**
     * Filter watch history by episode.
     *
     * @param string $episodeId
     * @return $this
     */
    public function forEpisode(string $episodeId): self
    {
        return $this->where('episode_id', $episodeId);
    }

    /**
     * Filter watch history by movie.
     *
     * @param string $movieId
     * @return $this
     */
    public function forMovie(string $movieId): self
    {
        return $this->whereHas('episode', function ($query) use ($movieId) {
            $query->where('movie_id', $movieId);
        });
    }

    /**
     * Filter watch history with progress greater than.
     *
     * @param int $seconds
     * @return $this
     */
    public function withProgressGreaterThan(int $seconds): self
    {
        return $this->where('progress_time', '>', $seconds);
    }

    /**
     * Filter watch history created within a date range.
     *
     * @param string $startDate
     * @param string $endDate
     * @return $this
     */
    public function createdBetween(string $startDate, string $endDate): self
    {
        return $this->whereBetween('created_at', [$startDate, $endDate]);
    }

    /**
     * Order watch history by progress time.
     *
     * @param string $direction
     * @return $this
     */
    public function orderByProgress(string $direction = 'desc'): self
    {
        return $this->orderBy('progress_time', $direction);
    }

    /**
     * Order watch history by creation date.
     *
     * @param string $direction
     * @return $this
     */
    public function orderByCreatedAt(string $direction = 'desc'): self
    {
        return $this->orderBy('created_at', $direction);
    }

    /**
     * Filter watch history with completed episodes (progress > 90%).
     *
     * @return $this
     */
    public function completed(): self
    {
        return $this->whereRaw('progress_time > (episodes.duration * 0.9)')
            ->join('episodes', 'watch_histories.episode_id', '=', 'episodes.id');
    }

    /**
     * Filter watch history with incomplete episodes (progress < 90%).
     *
     * @return $this
     */
    public function incomplete(): self
    {
        return $this->whereRaw('progress_time <= (episodes.duration * 0.9)')
            ->join('episodes', 'watch_histories.episode_id', '=', 'episodes.id');
    }
}

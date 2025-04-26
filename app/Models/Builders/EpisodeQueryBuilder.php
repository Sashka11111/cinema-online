<?php

namespace Liamtseva\Cinema\Models\Builders;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

class EpisodeQueryBuilder extends Builder
{
    /**
     * Filter episodes by movie.
     *
     * @param string $movieId
     * @return $this
     */
    public function forMovie(string $movieId): self
    {
        return $this->where('movie_id', $movieId);
    }

    /**
     * Filter episodes aired after a specific date.
     *
     * @param Carbon $date
     * @return $this
     */
    public function airedAfter(Carbon $date): self
    {
        return $this->where('air_date', '>=', $date);
    }

    /**
     * Filter episodes aired before a specific date.
     *
     * @param Carbon $date
     * @return $this
     */
    public function airedBefore(Carbon $date): self
    {
        return $this->where('air_date', '<=', $date);
    }

    /**
     * Filter episodes by number.
     *
     * @param int $number
     * @return $this
     */
    public function byNumber(int $number): self
    {
        return $this->where('number', $number);
    }



    /**
     * Filter episodes by name.
     *
     * @param string $name
     * @return $this
     */
    public function byName(string $name): self
    {
        return $this->where('name', 'like', "%{$name}%");
    }

    /**
     * Filter filler episodes.
     *
     * @return $this
     */
    public function fillers(): self
    {
        return $this->where('is_filler', true);
    }

    /**
     * Filter non-filler episodes.
     *
     * @return $this
     */
    public function nonFillers(): self
    {
        return $this->where('is_filler', false);
    }

    /**
     * Filter episodes by duration range.
     *
     * @param int $minDuration
     * @param int $maxDuration
     * @return $this
     */
    public function withDurationBetween(int $minDuration, int $maxDuration): self
    {
        return $this->whereBetween('duration', [$minDuration, $maxDuration]);
    }

    /**
     * Order episodes by number.
     *
     * @param string $direction
     * @return $this
     */
    public function orderByNumber(string $direction = 'asc'): self
    {
        return $this->orderBy('number', $direction);
    }

    /**
     * Order episodes by air date.
     *
     * @param string $direction
     * @return $this
     */
    public function orderByAirDate(string $direction = 'desc'): self
    {
        return $this->orderBy('air_date', $direction);
    }

    /**
     * Filter episodes with pictures.
     *
     * @return $this
     */
    public function withPictures(): self
    {
        return $this->whereRaw("pictures::text <> '[]'::text");
    }

    /**
     * Filter episodes with video players.
     *
     * @return $this
     */
    public function withVideoPlayers(): self
    {
        return $this->whereRaw("video_players::text <> '[]'::text");
    }

    /**
     * Filter episodes by air date range.
     *
     * @param string $startDate
     * @param string $endDate
     * @return $this
     */
    public function airedBetween(string $startDate, string $endDate): self
    {
        return $this->whereBetween('air_date', [$startDate, $endDate]);
    }
}

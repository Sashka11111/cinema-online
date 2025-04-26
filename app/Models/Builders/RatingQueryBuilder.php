<?php

namespace Liamtseva\Cinema\Models\Builders;

use Illuminate\Database\Eloquent\Builder;

class RatingQueryBuilder extends Builder
{
    /**
     * Filter ratings by user.
     *
     * @param string $userId
     * @return $this
     */
    public function forUser(string $userId): self
    {
        return $this->where('user_id', $userId);
    }

    /**
     * Filter ratings by movie.
     *
     * @param string $movieId
     * @return $this
     */
    public function forMovie(string $movieId): self
    {
        return $this->where('movie_id', $movieId);
    }

    /**
     * Filter ratings between a range.
     *
     * @param int $minRating
     * @param int $maxRating
     * @return $this
     */
    public function betweenRatings(int $minRating, int $maxRating): self
    {
        return $this->whereBetween('number', [$minRating, $maxRating]);
    }

    /**
     * Filter ratings with reviews.
     *
     * @return $this
     */
    public function withReviews(): self
    {
        return $this->whereNotNull('review');
    }

    /**
     * Filter ratings without reviews.
     *
     * @return $this
     */
    public function withoutReviews(): self
    {
        return $this->whereNull('review');
    }

    /**
     * Filter ratings by minimum rating.
     *
     * @param int $minRating
     * @return $this
     */
    public function minRating(int $minRating): self
    {
        return $this->where('number', '>=', $minRating);
    }

    /**
     * Filter ratings by maximum rating.
     *
     * @param int $maxRating
     * @return $this
     */
    public function maxRating(int $maxRating): self
    {
        return $this->where('number', '<=', $maxRating);
    }

    /**
     * Filter ratings created within a date range.
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
     * Order ratings by rating number.
     *
     * @param string $direction
     * @return $this
     */
    public function orderByRating(string $direction = 'desc'): self
    {
        return $this->orderBy('number', $direction);
    }

    /**
     * Order ratings by creation date.
     *
     * @param string $direction
     * @return $this
     */
    public function orderByCreatedAt(string $direction = 'desc'): self
    {
        return $this->orderBy('created_at', $direction);
    }
}

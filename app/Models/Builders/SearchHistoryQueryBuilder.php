<?php

namespace Liamtseva\Cinema\Models\Builders;

use Illuminate\Database\Eloquent\Builder;

class SearchHistoryQueryBuilder extends Builder
{
    /**
     * Filter search history by user.
     *
     * @param string $userId
     * @return $this
     */
    public function forUser(string $userId): self
    {
        return $this->where('user_id', $userId);
    }

    /**
     * Filter search history by query.
     *
     * @param string $query
     * @return $this
     */
    public function withQuery(string $query): self
    {
        return $this->where('query', 'like', "%{$query}%");
    }

    /**
     * Filter search history created within a date range.
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
     * Order search history by creation date.
     *
     * @param string $direction
     * @return $this
     */
    public function orderByCreatedAt(string $direction = 'desc'): self
    {
        return $this->orderBy('created_at', $direction);
    }

    /**
     * Group search history by query and count occurrences.
     *
     * @return $this
     */
    public function groupByQuery(): self
    {
        return $this->select('query')
            ->selectRaw('COUNT(*) as count')
            ->groupBy('query')
            ->orderByDesc('count');
    }

    /**
     * Filter search history with unique queries.
     *
     * @return $this
     */
    public function uniqueQueries(): self
    {
        return $this->select('query')
            ->distinct();
    }

    /**
     * Filter search history with queries of a minimum length.
     *
     * @param int $length
     * @return $this
     */
    public function withMinQueryLength(int $length): self
    {
        return $this->whereRaw("LENGTH(query) >= ?", [$length]);
    }
}

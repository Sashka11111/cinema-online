<?php

namespace Liamtseva\Cinema\Models\Builders;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class SelectionQueryBuilder extends Builder
{
    /**
     * Search selections by text.
     *
     * @param string $search
     * @return $this
     */
    public function search(string $search): self
    {
        return $this
            ->select('*')
            ->addSelect(DB::raw('similarity(name, ?) AS similarity'))
            ->whereRaw("searchable @@ websearch_to_tsquery('ukrainian', ?)", [$search, $search, $search, $search, $search])
            ->orWhereRaw('name % ?', [$search])
            ->orderByDesc('rank')
            ->orderByDesc('similarity');
    }

    /**
     * Filter selections by user.
     *
     * @param string $userId
     * @return $this
     */
    public function byUser(string $userId): self
    {
        return $this->where('user_id', $userId);
    }



    /**
     * Filter selections by name.
     *
     * @param string $name
     * @return $this
     */
    public function byName(string $name): self
    {
        return $this->where('name', 'like', "%{$name}%");
    }

    /**
     * Filter selections containing a specific movie.
     *
     * @param string $movieId
     * @return $this
     */
    public function containingMovie(string $movieId): self
    {
        return $this->whereHas('movies', function ($query) use ($movieId) {
            $query->where('movies.id', $movieId);
        });
    }

    /**
     * Filter selections containing a specific person.
     *
     * @param string $personId
     * @return $this
     */
    public function containingPerson(string $personId): self
    {
        return $this->whereHas('persons', function ($query) use ($personId) {
            $query->where('people.id', $personId);
        });
    }

    /**
     * Filter selections containing a specific episode.
     *
     * @param string $episodeId
     * @return $this
     */
    public function containingEpisode(string $episodeId): self
    {
        return $this->whereHas('episodes', function ($query) use ($episodeId) {
            $query->where('episodes.id', $episodeId);
        });
    }

    /**
     * Order selections by popularity (based on user lists count).
     *
     * @return $this
     */
    public function orderByPopularity(): self
    {
        return $this->withCount('userLists')->orderByDesc('user_lists_count');
    }

    /**
     * Order selections by items count.
     *
     * @return $this
     */
    public function orderByItemsCount(): self
    {
        return $this->withCount(['movies', 'persons', 'episodes'])
            ->orderByRaw('movies_count + persons_count + episodes_count DESC');
    }

    /**
     * Filter selections created within a date range.
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
     * Filter selections with description.
     *
     * @return $this
     */
    public function withDescription(): self
    {
        return $this->whereNotNull('description');
    }

    /**
     * Filter selections without description.
     *
     * @return $this
     */
    public function withoutDescription(): self
    {
        return $this->whereNull('description');
    }
}

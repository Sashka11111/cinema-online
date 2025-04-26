<?php

namespace Liamtseva\Cinema\Models\Builders;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class TagQueryBuilder extends Builder
{
    /**
     * Filter tags that are genres.
     *
     * @return $this
     */
    public function genres(): self
    {
        return $this->where('is_genre', true);
    }

    /**
     * Filter tags that are not genres.
     *
     * @return $this
     */
    public function notGenres(): self
    {
        return $this->where('is_genre', false);
    }

    /**
     * Search tags by text.
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
     * Filter tags by name.
     *
     * @param string $name
     * @return $this
     */
    public function byName(string $name): self
    {
        return $this->where('name', 'like', "%{$name}%");
    }

    /**
     * Filter tags by exact name.
     *
     * @param string $name
     * @return $this
     */
    public function byExactName(string $name): self
    {
        return $this->where('name', $name);
    }

    /**
     * Filter tags by movie.
     *
     * @param string $movieId
     * @return $this
     */
    public function forMovie(string $movieId): self
    {
        return $this->whereHas('movies', function ($query) use ($movieId) {
            $query->where('movies.id', $movieId);
        });
    }

    /**
     * Filter tags with images.
     *
     * @return $this
     */
    public function withImages(): self
    {
        return $this->whereNotNull('image');
    }

    /**
     * Filter tags without images.
     *
     * @return $this
     */
    public function withoutImages(): self
    {
        return $this->whereNull('image');
    }

    /**
     * Order tags by popularity (based on movies count).
     *
     * @return $this
     */
    public function orderByPopularity(): self
    {
        return $this->withCount('movies')->orderByDesc('movies_count');
    }


}

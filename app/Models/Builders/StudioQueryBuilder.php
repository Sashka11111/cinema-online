<?php

namespace Liamtseva\Cinema\Models\Builders;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class StudioQueryBuilder extends Builder
{
    /**
     * Filter studios by name.
     *
     * @param string $name
     * @return $this
     */
    public function byName(string $name): self
    {
        return $this->where('name', 'like', "%{$name}%");
    }

    /**
     * Search studios by text.
     *
     * @param string $search
     * @return $this
     */
    public function search(string $search): self
    {
        return $this
            ->select('studios.*')
            ->addSelect(DB::raw("ts_rank(studios.searchable, websearch_to_tsquery('ukrainian', ?)) AS rank"))
            ->addSelect(DB::raw('similarity(studios.name, ?) AS similarity'))
            ->where(function ($query) use ($search) {
                $query->whereRaw("studios.searchable @@ websearch_to_tsquery('ukrainian', ?)", [$search])
                      ->orWhereRaw('studios.name % ?', [$search]);
            })
            ->orderByDesc('rank')
            ->orderByDesc('similarity');
    }

    /**
     * Filter studios by exact name.
     *
     * @param string $name
     * @return $this
     */
    public function byExactName(string $name): self
    {
        return $this->where('name', $name);
    }



    /**
     * Filter studios with images.
     *
     * @return $this
     */
    public function withImages(): self
    {
        return $this->whereNotNull('image');
    }

    /**
     * Filter studios without images.
     *
     * @return $this
     */
    public function withoutImages(): self
    {
        return $this->whereNull('image');
    }

    /**
     * Order studios by popularity (based on movies count).
     *
     * @return $this
     */
    public function orderByPopularity(): self
    {
        return $this->withCount('movies')->orderByDesc('movies_count');
    }

    /**
     * Filter studios that have movies of a specific kind.
     *
     * @param string $kind
     * @return $this
     */
    public function withMoviesOfKind(string $kind): self
    {
        return $this->whereHas('movies', function ($query) use ($kind) {
            $query->where('kind', $kind);
        });
    }

    /**
     * Filter studios that have movies from a specific country.
     *
     * @param string $country
     * @return $this
     */
    public function withMoviesFromCountry(string $country): self
    {
        return $this->whereHas('movies', function ($query) use ($country) {
            $query->whereJsonContains('countries', $country);
        });
    }
}

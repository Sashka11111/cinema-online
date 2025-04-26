<?php

namespace Liamtseva\Cinema\Models\Builders;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Liamtseva\Cinema\Enums\Gender;
use Liamtseva\Cinema\Enums\PersonType;

class PersonQueryBuilder extends Builder
{
    /**
     * Filter people by type.
     *
     * @param PersonType $type
     * @return $this
     */
    public function byType(PersonType $type): self
    {
        return $this->where('type', $type->value);
    }

    /**
     * Filter people by name.
     *
     * @param string $name
     * @return $this
     */
    public function byName(string $name): self
    {
        return $this->where('name', 'like', "%{$name}%");
    }

    /**
     * Filter people by gender.
     *
     * @param Gender $gender
     * @return $this
     */
    public function byGender(Gender $gender): self
    {
        return $this->where('gender', $gender->value);
    }

    /**
     * Search people by text.
     *
     * @param string $search
     * @return $this
     */
    public function search(string $search): self
    {
        return $this
            ->select('people.*')
            ->addSelect(DB::raw("ts_rank(people.searchable, websearch_to_tsquery('ukrainian', ?)) AS rank"))
            ->addSelect(DB::raw('similarity(people.name, ?) AS similarity'))
            ->leftJoin('movie_person', 'people.id', '=', 'movie_person.person_id')
            ->whereRaw("people.searchable @@ websearch_to_tsquery('ukrainian', ?)", [$search, $search, $search, $search, $search, $search, $search])
            ->orWhereRaw('people.name % ?', [$search])
            ->orWhereRaw('movie_person.character_name % ?', [$search])
            ->orderByDesc('rank')
            ->orderByDesc('similarity');
    }

    /**
     * Filter people by movie.
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
     * Filter people by character name.
     *
     * @param string $characterName
     * @return $this
     */
    public function byCharacterName(string $characterName): self
    {
        return $this->whereHas('movies', function ($query) use ($characterName) {
            $query->where('character_name', 'like', "%{$characterName}%");
        });
    }

    /**
     * Filter people by age range.
     *
     * @param int $minAge
     * @param int $maxAge
     * @return $this
     */
    public function byAgeRange(int $minAge, int $maxAge): self
    {
        $minDate = now()->subYears($maxAge)->format('Y-m-d');
        $maxDate = now()->subYears($minAge)->format('Y-m-d');

        return $this->whereBetween('birthday', [$minDate, $maxDate]);
    }



    /**
     * Order people by popularity (based on movies count).
     *
     * @return $this
     */
    public function orderByPopularity(): self
    {
        return $this->withCount('movies')->orderByDesc('movies_count');
    }

    /**
     * Filter people with original name.
     *
     * @return $this
     */
    public function withOriginalName(): self
    {
        return $this->whereNotNull('original_name');
    }

    /**
     * Filter people by birth year.
     *
     * @param int $year
     * @return $this
     */
    public function bornInYear(int $year): self
    {
        return $this->whereYear('birthday', $year);
    }
}

<?php

namespace Liamtseva\Cinema\Models\Builders;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Liamtseva\Cinema\Enums\Country;
use Liamtseva\Cinema\Enums\Kind;
use Liamtseva\Cinema\Enums\Period;
use Liamtseva\Cinema\Enums\RestrictedRating;
use Liamtseva\Cinema\Enums\Source;
use Liamtseva\Cinema\Enums\Status;
use Liamtseva\Cinema\Enums\VideoQuality;

class MovieQueryBuilder extends Builder
{
    /**
     * Filter movies by kind.
     *
     * @return $this
     */
    public function ofKind(Kind $kind): self
    {
        return $this->where('kind', $kind->value);
    }

    /**
     * Filter movies by status.
     *
     * @return $this
     */
    public function withStatus(Status $status): self
    {
        return $this->where('status', $status->value);
    }

    /**
     * Filter movies by period.
     *
     * @return $this
     */
    public function ofPeriod(Period $period): self
    {
        return $this->where('period', $period->value);
    }

    /**
     * Filter movies by restricted rating.
     *
     * @return $this
     */
    public function withRestrictedRating(RestrictedRating $restrictedRating): self
    {
        return $this->where('restricted_rating', $restrictedRating->value);
    }

    /**
     * Filter movies by source.
     *
     * @return $this
     */
    public function fromSource(Source $source): self
    {
        return $this->where('source', $source->value);
    }

    /**
     * Filter movies by video quality.
     *
     * @return $this
     */
    public function withVideoQuality(VideoQuality $videoQuality): self
    {
        return $this->where('video_quality', $videoQuality->value);
    }

    /**
     * Filter movies by IMDb score.
     *
     * @return $this
     */
    public function withImdbScoreGreaterThan(float $score): self
    {
        return $this->where('imdb_score', '>=', $score);
    }

    /**
     * Filter movies by country.
     *
     * @return $this
     */
    public function fromCountry(Country $country): self
    {
        return $this->whereJsonContains('countries', $country->value);
    }

    /**
     * Search movies by text.
     *
     * @return $this
     */
    public function search(string $search): self
    {
        return $this->whereRaw("searchable @@ websearch_to_tsquery('ukrainian', ?)", [$search]);
    }

    /**
     * Filter movies by studio.
     *
     * @return $this
     */
    public function byStudio(string $studioId): self
    {
        return $this->where('studio_id', $studioId);
    }

    /**
     * Filter movies by tag.
     *
     * @return $this
     */
    public function withTag(string $tagId): self
    {
        return $this->whereHas('tags', function ($query) use ($tagId) {
            $query->where('tags.id', $tagId);
        });
    }

    /**
     * Filter movies by person.
     *
     * @return $this
     */
    public function withPerson(string $personId): self
    {
        return $this->whereHas('persons', function ($query) use ($personId) {
            $query->where('people.id', $personId);
        });
    }

    /**
     * Filter movies by release year.
     *
     * @return $this
     */
    public function releasedInYear(int $year): self
    {
        return $this->whereYear('first_air_date', $year);
    }

    /**
     * Filter movies by duration range.
     *
     * @return $this
     */
    public function withDurationBetween(int $minDuration, int $maxDuration): self
    {
        return $this->whereBetween('duration', [$minDuration, $maxDuration]);
    }

    /**
     * Filter published movies.
     *
     * @return $this
     */
    public function published(): self
    {
        return $this->where('is_published', true);
    }

    /**
     * Filter unpublished movies.
     *
     * @return $this
     */
    public function unpublished(): self
    {
        return $this->where('is_published', false);
    }

    /**
     * Order movies by popularity (based on ratings count).
     *
     * @return $this
     */
    public function orderByPopularity(): self
    {
        return $this->withCount('ratings')->orderByDesc('ratings_count');
    }

    /**
     * Order movies by average rating.
     *
     * @return $this
     */
    public function orderByRating(): self
    {
        return $this->withAvg('ratings', 'number')->orderByDesc('ratings_avg_number');
    }

    /**
     * Filter movies by release date range.
     *
     * @return $this
     */
    public function releasedBetween(string $startDate, string $endDate): self
    {
        return $this->whereBetween('first_air_date', [$startDate, $endDate]);
    }

    /**
     * Filter movies with episodes count greater than.
     *
     * @return $this
     */
    public function withEpisodesCountGreaterThan(int $count): self
    {
        return $this->where('episodes_count', '>=', $count);
    }
}

<?php

namespace Liamtseva\Cinema\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Liamtseva\Cinema\Enums\MovieRelateType;
use Liamtseva\Cinema\ValueObjects\MovieRelate;

class MovieRelateCast implements CastsAttributes
{
    /**
     * @return Collection<MovieRelate>
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        $collection = collect(json_decode($value, true));

        return $collection->isNotEmpty() ? $collection
            ->map(fn ($item) => new MovieRelate($item['movie_id'], MovieRelateType::from($item['type']))) : $collection;
    }

    /**
     * @param  Collection<MovieRelate>|array  $value
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): string
    {
        if (! $value instanceof Collection) {
            $value = collect($value);
        }

        return json_encode($value->map(fn (MovieRelate $mr) => ['movie_id' => $mr->movie_id, 'type' => $mr->type])->toArray());
    }
}

<?php

namespace Liamtseva\Cinema\Models;

use Database\Factories\RatingFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Liamtseva\Cinema\Models\Builders\RatingQueryBuilder;

/**
 * @mixin IdeHelperRating
 */
class Rating extends Model
{
    /** @use HasFactory<RatingFactory> */
    use HasFactory, HasUlids;

    public function newEloquentBuilder($query): RatingQueryBuilder
    {
        return new RatingQueryBuilder($query);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function movie(): BelongsTo
    {
        return $this->belongsTo(Movie::class);
    }

    public function review(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value, array $attributes) => nl2br(e($attributes['review'])),
            set: fn (mixed $value) => trim($value)
        );
    }
}

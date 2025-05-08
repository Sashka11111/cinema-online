<?php

namespace Liamtseva\Cinema\Models;

use Database\Factories\MovieFactory;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Casts\AsCollection;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Liamtseva\Cinema\Enums\Kind;
use Liamtseva\Cinema\Enums\Period;
use Liamtseva\Cinema\Enums\RestrictedRating;
use Liamtseva\Cinema\Enums\Source;
use Liamtseva\Cinema\Enums\Status;
use Liamtseva\Cinema\Models\Builders\MovieQueryBuilder;
use Liamtseva\Cinema\Models\Scopes\PublishedScope;
use Liamtseva\Cinema\Models\Traits\HasSeo;

/**
 * @mixin IdeHelperMovie
 */
#[ScopedBy([PublishedScope::class])]
class Movie extends Model
{
    /** @use HasFactory<MovieFactory> */
    use HasFactory, HasSeo, HasUlids;

    protected $hidden = ['searchable'];

    protected $casts = [
        'aliases' => AsCollection::class,
        'countries' => AsCollection::class,
        'attachments' => AsCollection::class,
        'related' => AsCollection::class,
        'similars' => AsCollection::class,
        'imdb_score' => 'float',
        'first_air_date' => 'date',
        'last_air_date' => 'date',
        'api_sources' => AsCollection::class,
        'kind' => Kind::class,
        'status' => Status::class,
        'period' => Period::class,
        'restricted_rating' => RestrictedRating::class,
        'source' => Source::class,
        'is_published' => 'boolean',
    ];

    // Додаємо аксесор для is_public, який використовує is_published
    protected function isPublic(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->is_published,
            set: fn ($value) => ['is_published' => $value]
        );
    }

    public function newEloquentBuilder($query): MovieQueryBuilder
    {
        return new MovieQueryBuilder($query);
    }

    public function studio(): BelongsTo
    {
        return $this->belongsTo(Studio::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function persons(): BelongsToMany
    {
        return $this->belongsToMany(Person::class)
            ->withPivot('character_name');
    }

    public function episodes(): HasMany
    {
        return $this->hasMany(Episode::class)->chaperone();
    }

    public function userLists(): MorphMany
    {
        return $this->morphMany(UserList::class, 'listable');
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function selections(): MorphToMany
    {
        return $this->morphToMany(Selection::class, 'selectionable');
    }

    protected function posterUrl(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $this->poster ? asset("storage/$this->poster") : null
        );
    }
}

<?php

namespace Liamtseva\Cinema\Models;

use Database\Factories\SelectionFactory;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Liamtseva\Cinema\Models\Builders\SelectionQueryBuilder;
use Liamtseva\Cinema\Models\Traits\HasSeo;

/**
 * @mixin IdeHelperSelection
 */
class Selection extends Model
{
    /** @use HasFactory<SelectionFactory> */
    use HasFactory, HasSeo, HasUlids;

    protected $hidden = ['searchable'];

    public function newEloquentBuilder($query): SelectionQueryBuilder
    {
        return new SelectionQueryBuilder($query);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function movies(): MorphToMany
    {
        return $this->morphedByMany(Movie::class, 'selectionable');
    }

    public function persons(): MorphToMany
    {
        return $this->morphedByMany(Person::class, 'selectionable');
    }

    public function userLists(): MorphMany
    {
        return $this->morphMany(UserList::class, 'listable');
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function episodes(): MorphToMany
    {
        return $this->morphedByMany(Episode::class, 'selectionable');
    }
}

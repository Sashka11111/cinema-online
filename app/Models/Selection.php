<?php

namespace Liamtseva\Cinema\Models;

use Database\Factories\SelectionFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Facades\DB;
use Liamtseva\Cinema\Models\Traits\HasSeo;

/**
 * @mixin IdeHelperSelection
 */
class Selection extends Model
{
    /** @use HasFactory<SelectionFactory> */
    use HasFactory, HasSeo, HasUlids;

    protected $hidden = ['searchable'];

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

    public function scopeSearch(Builder $query, string $search): Builder
    {
        return $query
            ->select('*')
            ->addSelect(DB::raw("ts_rank(searchable, websearch_to_tsquery('ukrainian', ?)) AS rank"))
            ->addSelect(DB::raw("ts_headline('ukrainian', name, websearch_to_tsquery('ukrainian', ?), 'HighlightAll=true') AS name_highlight"))
            ->addSelect(DB::raw("ts_headline('ukrainian', description, websearch_to_tsquery('ukrainian', ?), 'HighlightAll=true') AS description_highlight"))
            ->addSelect(DB::raw('similarity(name, ?) AS similarity'))
            ->whereRaw("searchable @@ websearch_to_tsquery('ukrainian', ?)", [$search, $search, $search, $search, $search])
            ->orWhereRaw('name % ?', [$search])
            ->orderByDesc('rank')
            ->orderByDesc('similarity');
    }
}

<?php

namespace Liamtseva\Cinema\Models;

use Database\Factories\StudioFactory;
use DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Liamtseva\Cinema\Models\Traits\HasSeo;

/**
 * @mixin IdeHelperStudio
 */
class Studio extends Model
{
    /** @use HasFactory<StudioFactory> */
    use HasFactory, HasSeo, HasUlids;

    protected $hidden = ['searchable'];

    public function movies(): HasMany
    {
        return $this->hasMany(Movie::class);
    }

    // TODO: fulltext search
    public function scopeByName(Builder $query, string $name): Builder
    {
        return $query->where('name', 'like', '%'.$name.'%');
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

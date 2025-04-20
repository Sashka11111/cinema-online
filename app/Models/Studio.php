<?php

namespace Liamtseva\Cinema\Models;

use Database\Factories\StudioFactory;
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
            ->whereRaw("searchable @@ websearch_to_tsquery('ukrainian', ?)", [$search])
            ->orWhereRaw('name % ?', [$search]);
    }
}

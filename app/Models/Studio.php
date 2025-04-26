<?php

namespace Liamtseva\Cinema\Models;

use Database\Factories\StudioFactory;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Liamtseva\Cinema\Models\Builders\StudioQueryBuilder;
use Liamtseva\Cinema\Models\Traits\HasSeo;

/**
 * @mixin IdeHelperStudio
 */
class Studio extends Model
{
    /** @use HasFactory<StudioFactory> */
    use HasFactory, HasSeo, HasUlids;

    protected $hidden = ['searchable'];

    public function newEloquentBuilder($query): StudioQueryBuilder
    {
        return new StudioQueryBuilder($query);
    }

    public function movies(): HasMany
    {
        return $this->hasMany(Movie::class);
    }
}

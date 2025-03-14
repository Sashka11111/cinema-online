<?php

namespace Liamtseva\Cinema\Models\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Str;

trait HasSeo
{
    public function scopeBySlug(Builder $query, string $slug): Builder
    {
        return $query->where('slug', $slug);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function metaImage(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? asset("storage/$value") : null
        );
    }

    public static function makeMetaDescription(string $description): string
    {
        return Str::length($description) > 376 ? Str::substr($description, 0, 373).'...' : $description;
    }
}

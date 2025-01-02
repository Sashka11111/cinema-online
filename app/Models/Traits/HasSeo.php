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

    protected function metaImage(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? asset("storage/$value") : null
        );
    }

    protected function slug(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value,
            set: fn ($value) => str($value)->slug().'-'.str(str()->random(6))->lower()
        );
    }

    protected function metaDescription(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value,
            set: fn ($value) => Str::length($value) > 376 ? Str::substr($value, 0, 373).'...' : $value
        );
    }
}

<?php

namespace Liamtseva\Cinema\Models;

use Database\Factories\UserListFactory;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Liamtseva\Cinema\Enums\UserListType;
use Liamtseva\Cinema\Models\Builders\UserListQueryBuilder;

/**
 * @mixin IdeHelperUserList
 */
class UserList extends Model
{
    /** @use HasFactory<UserListFactory> */
    use HasFactory, HasUlids;

    protected $casts = [
        'type' => UserListType::class,
    ];

    public function newEloquentBuilder($query): UserListQueryBuilder
    {
        return new UserListQueryBuilder($query);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function listable(): MorphTo
    {
        return $this->morphTo();
    }

    public function getTranslatedTypeAttribute(): string
    {
        return match ($this->listable_type) {
            Movie::class => 'Фільм',
            Episode::class => 'Епізод',
            Selection::class => 'Підбірка',
            Person::class => 'Персона',
            Tag::class => 'Тег',
            default => 'Невідомий контент',
        };
    }
}

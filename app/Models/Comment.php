<?php

namespace Liamtseva\Cinema\Models;

use Database\Factories\CommentFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @mixin IdeHelperComment
 */
class Comment extends Model
{
    /** @use HasFactory<CommentFactory> */
    use HasFactory, HasUlids;

    public function commentable(): MorphTo
    {
        return $this->morphTo();
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function likes(): HasMany
    {
        return $this->hasMany(CommentLike::class)->chaperone();
    }

    public function reports(): HasMany
    {
        return $this->hasMany(CommentReport::class)->chaperone();
    }

    public function scopeReplies(Builder $query): Builder
    {
        return $query->whereNotNull('parent_id');
    }

    public function scopeRoots(Builder $query): Builder
    {
        return $query->whereNull('parent_id');
    }

    public function isRoot(): bool
    {
        return $this->parent_id === null;
    }

    public function childrenCount(): int
    {
        return $this->children()->count();
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id')->chaperone();
    }

    public function excerpt(int $length = 50): string
    {
        return str()->limit($this->body, $length);
    }

    protected function isReply(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->parent_id !== null
        );
    }

    protected function body(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => trim($value)
        );
    }
}

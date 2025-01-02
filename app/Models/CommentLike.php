<?php

namespace Liamtseva\Cinema\Models;

use Database\Factories\CommentLikeFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperCommentLike
 */
class CommentLike extends Model
{
    /** @use HasFactory<CommentLikeFactory> */
    use HasFactory, HasUlids;

    public function comment(): BelongsTo
    {
        return $this->belongsTo(Comment::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeByUser(Builder $query, string $userId): Builder
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByComment(Builder $query, string $commentId): Builder
    {
        return $query->where('comment_id', $commentId);
    }

    public function scopeOnlyLikes(Builder $query): Builder
    {
        return $query->where('is_liked', true);
    }

    public function scopeOnlyDislikes(Builder $query): Builder
    {
        return $query->where('is_liked', false);
    }
}

<?php

namespace Liamtseva\Cinema\Models;

use Database\Factories\CommentLikeFactory;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Liamtseva\Cinema\Models\Builders\CommentLikeQueryBuilder;

/**
 * @mixin IdeHelperCommentLike
 */
class CommentLike extends Model
{
    /** @use HasFactory<CommentLikeFactory> */
    use HasFactory, HasUlids;

    public function newEloquentBuilder($query): CommentLikeQueryBuilder
    {
        return new CommentLikeQueryBuilder($query);
    }

    public function comment(): BelongsTo
    {
        return $this->belongsTo(Comment::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

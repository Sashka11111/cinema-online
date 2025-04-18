<?php

namespace Liamtseva\Cinema\Models;

use Database\Factories\CommentReportFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Liamtseva\Cinema\Enums\CommentReportType;

/**
 * @mixin IdeHelperCommentReport
 */
class CommentReport extends Model
{
    /** @use HasFactory<CommentReportFactory> */
    use HasFactory, HasUlids;

    protected $casts = [
        'type' => CommentReportType::class,
    ];

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

    public function scopeUnViewed(Builder $query): Builder
    {
        return $query->where('is_viewed', false);
    }
}

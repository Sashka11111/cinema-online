<?php

namespace Liamtseva\Cinema\Models;

use Database\Factories\CommentReportFactory;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Liamtseva\Cinema\Enums\CommentReportType;
use Liamtseva\Cinema\Models\Builders\CommentReportQueryBuilder;

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

    public function newEloquentBuilder($query): CommentReportQueryBuilder
    {
        return new CommentReportQueryBuilder($query);
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

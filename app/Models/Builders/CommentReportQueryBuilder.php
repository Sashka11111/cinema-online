<?php

namespace Liamtseva\Cinema\Models\Builders;

use Illuminate\Database\Eloquent\Builder;
use Liamtseva\Cinema\Enums\CommentReportType;

class CommentReportQueryBuilder extends Builder
{
    /**
     * Filter comment reports by user.
     *
     * @param string $userId
     * @return $this
     */
    public function byUser(string $userId): self
    {
        return $this->where('user_id', $userId);
    }

    /**
     * Filter comment reports by comment.
     *
     * @param string $commentId
     * @return $this
     */
    public function byComment(string $commentId): self
    {
        return $this->where('comment_id', $commentId);
    }

    /**
     * Filter unviewed comment reports.
     *
     * @return $this
     */
    public function unViewed(): self
    {
        return $this->where('is_viewed', false);
    }

    /**
     * Filter viewed comment reports.
     *
     * @return $this
     */
    public function viewed(): self
    {
        return $this->where('is_viewed', true);
    }

    /**
     * Filter comment reports by type.
     *
     * @param CommentReportType $type
     * @return $this
     */
    public function byType(CommentReportType $type): self
    {
        return $this->where('type', $type->value);
    }

    /**
     * Filter comment reports with body content.
     *
     * @return $this
     */
    public function withBody(): self
    {
        return $this->whereNotNull('body');
    }

    /**
     * Filter comment reports without body content.
     *
     * @return $this
     */
    public function withoutBody(): self
    {
        return $this->whereNull('body');
    }

    /**
     * Filter comment reports created within a date range.
     *
     * @param string $startDate
     * @param string $endDate
     * @return $this
     */
    public function createdBetween(string $startDate, string $endDate): self
    {
        return $this->whereBetween('created_at', [$startDate, $endDate]);
    }

    /**
     * Order comment reports by creation date.
     *
     * @param string $direction
     * @return $this
     */
    public function orderByCreatedAt(string $direction = 'desc'): self
    {
        return $this->orderBy('created_at', $direction);
    }
}

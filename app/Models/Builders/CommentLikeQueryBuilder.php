<?php

namespace Liamtseva\Cinema\Models\Builders;

use Illuminate\Database\Eloquent\Builder;

class CommentLikeQueryBuilder extends Builder
{
    /**
     * Filter comment likes by user.
     *
     * @param string $userId
     * @return $this
     */
    public function byUser(string $userId): self
    {
        return $this->where('user_id', $userId);
    }

    /**
     * Filter comment likes by comment.
     *
     * @param string $commentId
     * @return $this
     */
    public function byComment(string $commentId): self
    {
        return $this->where('comment_id', $commentId);
    }

    /**
     * Filter only likes.
     *
     * @return $this
     */
    public function onlyLikes(): self
    {
        return $this->where('is_liked', true);
    }

    /**
     * Filter only dislikes.
     *
     * @return $this
     */
    public function onlyDislikes(): self
    {
        return $this->where('is_liked', false);
    }

    /**
     * Filter comment likes created within a date range.
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
     * Filter comment likes by user and comment.
     *
     * @param string $userId
     * @param string $commentId
     * @return $this
     */
    public function byUserAndComment(string $userId, string $commentId): self
    {
        return $this->where('user_id', $userId)
            ->where('comment_id', $commentId);
    }
}

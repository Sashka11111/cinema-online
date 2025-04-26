<?php

namespace Liamtseva\Cinema\Models\Builders;

use Illuminate\Database\Eloquent\Builder;

class CommentQueryBuilder extends Builder
{
    /**
     * Filter reply comments.
     *
     * @return $this
     */
    public function replies(): self
    {
        return $this->whereNotNull('parent_id');
    }

    /**
     * Filter root comments.
     *
     * @return $this
     */
    public function roots(): self
    {
        return $this->whereNull('parent_id');
    }

    /**
     * Filter comments by user.
     *
     * @param string $userId
     * @return $this
     */
    public function byUser(string $userId): self
    {
        return $this->where('user_id', $userId);
    }

    /**
     * Filter comments by parent.
     *
     * @param string $parentId
     * @return $this
     */
    public function byParent(string $parentId): self
    {
        return $this->where('parent_id', $parentId);
    }

    /**
     * Filter comments by commentable type and ID.
     *
     * @param string $type
     * @param string $id
     * @return $this
     */
    public function forCommentable(string $type, string $id): self
    {
        return $this->where('commentable_type', $type)
            ->where('commentable_id', $id);
    }

    /**
     * Filter comments by body content.
     *
     * @param string $content
     * @return $this
     */
    public function containingText(string $content): self
    {
        return $this->where('body', 'like', "%{$content}%");
    }

    /**
     * Filter spoiler comments.
     *
     * @return $this
     */
    public function spoilers(): self
    {
        return $this->where('is_spoiler', true);
    }

    /**
     * Filter non-spoiler comments.
     *
     * @return $this
     */
    public function nonSpoilers(): self
    {
        return $this->where('is_spoiler', false);
    }

    /**
     * Order comments by likes count.
     *
     * @return $this
     */
    public function orderByLikes(): self
    {
        return $this->withCount(['likes' => function ($query) {
            $query->where('is_liked', true);
        }])->orderByDesc('likes_count');
    }

    /**
     * Order comments by dislikes count.
     *
     * @return $this
     */
    public function orderByDislikes(): self
    {
        return $this->withCount(['likes' => function ($query) {
            $query->where('is_liked', false);
        }])->orderByDesc('likes_count');
    }

    /**
     * Filter comments with reports.
     *
     * @return $this
     */
    public function withReports(): self
    {
        return $this->has('reports');
    }

    /**
     * Filter comments created within a date range.
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
     * Filter comments with replies.
     *
     * @return $this
     */
    public function withReplies(): self
    {
        return $this->has('children');
    }

    /**
     * Filter comments without replies.
     *
     * @return $this
     */
    public function withoutReplies(): self
    {
        return $this->doesntHave('children');
    }
}

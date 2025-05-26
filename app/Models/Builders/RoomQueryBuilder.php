<?php

namespace Liamtseva\Cinema\Models\Builders;

use Illuminate\Database\Eloquent\Builder;
use Liamtseva\Cinema\Enums\RoomStatus;

class RoomQueryBuilder extends Builder
{
    /**
     * Filter active rooms.
     *
     * @return $this
     */
    public function active(): self
    {
        return $this->where('room_status', RoomStatus::ACTIVE);
    }

    /**
     * Filter completed rooms.
     *
     * @return $this
     */
    public function completed(): self
    {
        return $this->where('room_status', RoomStatus::COMPLETED);
    }

    /**
     * Filter rooms that have not started.
     *
     * @return $this
     */
    public function notStarted(): self
    {
        return $this->where('room_status', RoomStatus::NOT_STARTED);
    }

    /**
     * Filter public rooms.
     *
     * @return $this
     */
    public function public(): self
    {
        return $this->where('is_private', false);
    }

    /**
     * Filter private rooms.
     *
     * @return $this
     */
    public function private(): self
    {
        return $this->where('is_private', true);
    }

    /**
     * Filter rooms created by a specific user.
     *
     * @param  string  $userId
     * @return $this
     */
    public function byUser($userId): self
    {
        return $this->where('user_id', $userId);
    }

    /**
     * Filter rooms for a specific episode.
     *
     * @param  string  $episodeId
     * @return $this
     */
    public function forEpisode($episodeId): self
    {
        return $this->where('episode_id', $episodeId);
    }

    /**
     * Filter rooms that are not full.
     *
     * @return $this
     */
    public function notFull(): self
    {
        return $this->whereHas('viewers', function ($query) {
            $query->whereNull('room_user.left_at')
                ->groupBy('room_id')
                ->havingRaw('COUNT(*) < rooms.max_viewers');
        });
    }

    /**
     * Order rooms by number of active viewers (descending).
     *
     * @return $this
     */
    public function orderByPopularity(): self
    {
        return $this->withCount(['viewers' => function ($query) {
            $query->whereNull('room_user.left_at');
        }])
            ->orderByDesc('viewers_count');
    }

    /**
     * Order rooms by creation date (descending).
     *
     * @param  string|null  $column
     * @return $this
     */
    public function latest($column = null): self
    {
        return $this->orderByDesc($column ?: $this->model->getCreatedAtColumn());
    }
}

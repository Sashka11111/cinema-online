<?php

namespace Liamtseva\Cinema\Models\Builders;

use Illuminate\Database\Eloquent\Builder;
use Liamtseva\Cinema\Enums\UserListType;

class UserListQueryBuilder extends Builder
{
    /**
     * Filter user lists by type.
     *
     * @param UserListType $type
     * @return $this
     */
    public function ofType(UserListType $type): self
    {
        return $this->where('type', $type->value);
    }

    /**
     * Filter user lists by user, listable class, and type.
     *
     * @param string $userId
     * @param string|null $listableClass
     * @param UserListType|null $userListType
     * @return $this
     */
    public function forUser(
        string $userId,
        ?string $listableClass = null,
        ?UserListType $userListType = null
    ): self {
        return $this->where('user_id', $userId)
            ->when($listableClass, function ($query) use ($listableClass) {
                $query->where('listable_type', $listableClass);
            })
            ->when($userListType, function ($query) use ($userListType) {
                $query->where('type', $userListType->value);
            });
    }

    /**
     * Filter user lists by listable type and ID.
     *
     * @param string $listableType
     * @param string $listableId
     * @return $this
     */
    public function forListable(string $listableType, string $listableId): self
    {
        return $this->where('listable_type', $listableType)
            ->where('listable_id', $listableId);
    }

    /**
     * Filter user lists created within a date range.
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
     * Filter user lists by user and type.
     *
     * @param string $userId
     * @param UserListType $type
     * @return $this
     */
    public function forUserOfType(string $userId, UserListType $type): self
    {
        return $this->where('user_id', $userId)
            ->where('type', $type->value);
    }

    /**
     * Filter favorite user lists.
     *
     * @return $this
     */
    public function favorites(): self
    {
        return $this->where('type', UserListType::FAVORITE->value);
    }

    /**
     * Filter watching user lists.
     *
     * @return $this
     */
    public function watching(): self
    {
        return $this->where('type', UserListType::WATCHING->value);
    }

    /**
     * Filter planned user lists.
     *
     * @return $this
     */
    public function planned(): self
    {
        return $this->where('type', UserListType::PLANNED->value);
    }

    /**
     * Filter watched user lists.
     *
     * @return $this
     */
    public function watched(): self
    {
        return $this->where('type', UserListType::WATCHED->value);
    }

    /**
     * Filter not watching user lists.
     *
     * @return $this
     */
    public function notWatching(): self
    {
        return $this->where('type', UserListType::NOT_WATCHING->value);
    }

    /**
     * Filter stopped user lists.
     *
     * @return $this
     */
    public function stopped(): self
    {
        return $this->where('type', UserListType::STOPPED->value);
    }

    /**
     * Filter rewatching user lists.
     *
     * @return $this
     */
    public function rewatching(): self
    {
        return $this->where('type', UserListType::REWATCHING->value);
    }
}

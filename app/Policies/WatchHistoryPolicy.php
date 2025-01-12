<?php

namespace Liamtseva\Cinema\Policies;

use Liamtseva\Cinema\Models\User;
use Liamtseva\Cinema\Models\WatchHistory;

class WatchHistoryPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Дозволити перегляд історії переглядів для авторизованих користувачів
        return $user->isAuthenticated();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, WatchHistory $watchHistory): bool
    {
        // Дозволити перегляд, якщо це історія переглядів самого користувача
        return $user->id === $watchHistory->user_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Дозволити створення запису історії переглядів для авторизованих користувачів
        return $user->isAuthenticated();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, WatchHistory $watchHistory): bool
    {
        // Дозволити оновлення, якщо це історія переглядів самого користувача
        return $user->id === $watchHistory->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, WatchHistory $watchHistory): bool
    {
        // Дозволити видалення, якщо це історія переглядів самого користувача
        return $user->id === $watchHistory->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, WatchHistory $watchHistory): bool
    {
        // Дозволити відновлення, якщо це історія переглядів самого користувача
        return $user->id === $watchHistory->user_id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, WatchHistory $watchHistory): bool
    {
        // Дозволити остаточне видалення, якщо це історія переглядів самого користувача
        return $user->id === $watchHistory->user_id;
    }
}

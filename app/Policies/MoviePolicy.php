<?php

namespace Liamtseva\Cinema\Policies;

use Liamtseva\Cinema\Models\Movie;
use Liamtseva\Cinema\Models\User;

class MoviePolicy
{
    public function before(User $user, $ability): ?bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return null;
    }

    /**
     * Усі користувачі (включаючи неавторизованих) можуть переглядати список фільмів.
     */
    public function viewAny(?User $user): bool
    {
        return true;
    }

    /**
     * Усі користувачі (включаючи неавторизованих) можуть переглядати публічні фільми, адміністратори — усі.
     */
    public function view(?User $user, Movie $movie): bool
    {
        return $movie->is_public || ($user && $user->isAdmin());
    }

    /**
     * Тільки адміністратор може створювати нові фільми.
     */
    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Дозволяється, якщо користувач є адміністратором або власником фільму.
     */
    public function update(User $user, Movie $movie): bool
    {
        return $user->isAdmin() || $user->id === $movie->user_id;
    }

    /**
     * Тільки адміністратор може видаляти фільми.
     */
    public function delete(User $user, Movie $movie): bool
    {
        return $user->isAdmin();
    }

    /**
     * Тільки адміністратор може відновлювати фільми.
     */
    public function restore(User $user, Movie $movie): bool
    {
        return $user->isAdmin();
    }

    /**
     * Тільки адміністратор може остаточно видаляти фільми.
     */
    public function forceDelete(User $user, Movie $movie): bool
    {
        return $user->isAdmin();
    }
}

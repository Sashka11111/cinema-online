<?php

namespace Liamtseva\Cinema\Policies;

use Liamtseva\Cinema\Models\Movie;
use Liamtseva\Cinema\Models\User;

class MoviePolicy
{
    /**
     * Determine whether the user can view any models.
     * Наприклад, усі авторизовані користувачі можуть переглядати список фільмів.
     */
    public function viewAny(User $user): bool
    {
        return $user->isAuthenticated(); // Перевіряємо, чи користувач авторизований
    }

    /**
     * Determine whether the user can view the model.
     * Наприклад, публічні фільми доступні всім, інші — тільки адміністратору.
     */
    public function view(User $user, Movie $movie): bool
    {
        return $movie->is_public || $user->is_admin; // Публічні фільми або доступ для адміністратора
    }

    /**
     * Determine whether the user can create models.
     * Наприклад, тільки адміністратор може створювати нові фільми.
     */
    public function create(User $user): bool
    {
        return $user->is_admin; // Дозвіл тільки адміністратору
    }

    /**
     * Determine whether the user can update the model.
     * Наприклад, адміністратор або автор фільму можуть його редагувати.
     */
    public function update(User $user, Movie $movie): bool
    {
        return $user->is_admin || $user->id === $movie->author_id; // Перевірка прав доступу
    }

    /**
     * Determine whether the user can delete the model.
     * Наприклад, тільки адміністратор може видаляти фільми.
     */
    public function delete(User $user, Movie $movie): bool
    {
        return $user->is_admin; // Доступ до видалення тільки адміністратору
    }

    /**
     * Determine whether the user can restore the model.
     * Наприклад, відновлення фільмів доступне лише адміністратору.
     */
    public function restore(User $user, Movie $movie): bool
    {
        return $user->is_admin; // Відновлення доступне тільки адміністратору
    }

    /**
     * Determine whether the user can permanently delete the model.
     * Наприклад, тільки адміністратор може остаточно видалити фільми.
     */
    public function forceDelete(User $user, Movie $movie): bool
    {
        return $user->is_admin; // Остаточне видалення дозволено тільки адміністратору
    }
}

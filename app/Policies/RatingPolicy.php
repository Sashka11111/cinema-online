<?php

namespace Liamtseva\Cinema\Policies;

use Liamtseva\Cinema\Models\Rating;
use Liamtseva\Cinema\Models\User;

class RatingPolicy
{
    public function before(User $user, $ability): ?bool
    {
        // Якщо користувач адміністратор, дозволяємо всі дії
        if ($user->isAdmin()) {
            return true;
        }

        return null;
    }

    /**
     * Determine whether the user can view any models.
     * Усі авторизовані користувачі можуть переглядати список оцінок.
     */
    public function viewAny(User $user): bool
    {
        return $user->isAuthenticated(); // Перевірка авторизації користувача
    }

    /**
     * Determine whether the user can view the model.
     * Дозволяється переглядати, якщо користувач є адміністратором
     * або автором оцінки.
     */
    public function view(User $user, Rating $rating): bool
    {
        return $user->isAdmin() || $user->id === $rating->user_id;
    }

    /**
     * Determine whether the user can create models.
     * Тільки авторизовані користувачі можуть створювати оцінки.
     */
    public function create(User $user): bool
    {
        return $user->isAuthenticated();
    }

    /**
     * Determine whether the user can update the model.
     * Дозволяється, якщо користувач є адміністратором
     * або автором оцінки.
     */
    public function update(User $user, Rating $rating): bool
    {
        return $user->isAdmin() || $user->id === $rating->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     * Дозволяється, якщо користувач є адміністратором
     * або автором оцінки.
     */
    public function delete(User $user, Rating $rating): bool
    {
        return $user->isAdmin() || $user->id === $rating->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     * Тільки адміністратор може відновлювати оцінки.
     */
    public function restore(User $user, Rating $rating): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can permanently delete the model.
     * Тільки адміністратор може остаточно видаляти оцінки.
     */
    public function forceDelete(User $user, Rating $rating): bool
    {
        return $user->isAdmin();
    }
}

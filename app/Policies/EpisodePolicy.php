<?php

namespace Liamtseva\Cinema\Policies;

use Liamtseva\Cinema\Models\Episode;
use Liamtseva\Cinema\Models\User;

class EpisodePolicy
{
    /**
     * Determine whether the user can view any models.
     * Наприклад, усі авторизовані користувачі можуть переглядати список епізодів.
     */
    public function viewAny(User $user): bool
    {
        return $user->isAuthenticated(); // Перевіряємо, чи користувач авторизований
    }

    /**
     * Determine whether the user can view the model.
     * Наприклад, усі користувачі можуть переглядати доступні епізоди.
     */
    public function view(User $user, Episode $episode): bool
    {
        return $episode->is_public || $user->is_admin; // Публічні епізоди або доступ адміністратору
    }

    /**
     * Determine whether the user can create models.
     * Наприклад, тільки адміністратор може створювати нові епізоди.
     */
    public function create(User $user): bool
    {
        return $user->is_admin; // Тільки адміністратор має право створювати
    }

    /**
     * Determine whether the user can update the model.
     * Наприклад, адміністратор або автор епізоду можуть його редагувати.
     */
    public function update(User $user, Episode $episode): bool
    {
        return $user->is_admin || $user->id === $episode->author_id; // Перевірка прав
    }

    /**
     * Determine whether the user can delete the model.
     * Наприклад, тільки адміністратор може видаляти епізоди.
     */
    public function delete(User $user, Episode $episode): bool
    {
        return $user->is_admin; // Тільки адміністратор
    }

    /**
     * Determine whether the user can restore the model.
     * Наприклад, відновлення доступне тільки адміністратору.
     */
    public function restore(User $user, Episode $episode): bool
    {
        return $user->is_admin; // Відновлення дозволено адміністратору
    }

    /**
     * Determine whether the user can permanently delete the model.
     * Наприклад, остаточне видалення доступне тільки адміністратору.
     */
    public function forceDelete(User $user, Episode $episode): bool
    {
        return $user->is_admin; // Остаточне видалення дозволено адміністратору
    }
}

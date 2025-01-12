<?php

namespace Liamtseva\Cinema\Policies;

use Liamtseva\Cinema\Models\CommentReport;
use Liamtseva\Cinema\Models\User;

class CommentReportPolicy
{
    /**
     * Determine whether the user can view any models.
     * Наприклад, адміністратор може переглядати всі звіти.
     */
    public function viewAny(User $user): bool
    {
        return $user->is_admin; // Тільки адміністратор може переглядати всі звіти
    }

    /**
     * Determine whether the user can view the model.
     * Наприклад, користувач може переглядати звіт, якщо він його створив або якщо він адміністратор.
     */
    public function view(User $user, CommentReport $commentReport): bool
    {
        return $user->id === $commentReport->user_id || $user->is_admin;
    }

    /**
     * Determine whether the user can create models.
     * Наприклад, будь-який авторизований користувач може створити звіт.
     */
    public function create(User $user): bool
    {
        return $user->isAuthenticated(); // Припустимо, метод isAuthenticated() перевіряє авторизацію
    }

    /**
     * Determine whether the user can update the model.
     * Наприклад, користувач не може редагувати звіт, це доступно тільки адміністратору.
     */
    public function update(User $user, CommentReport $commentReport): bool
    {
        return $user->is_admin; // Тільки адміністратор може редагувати звіт
    }

    /**
     * Determine whether the user can delete the model.
     * Наприклад, звіт може видалити адміністратор або користувач, який його створив.
     */
    public function delete(User $user, CommentReport $commentReport): bool
    {
        return $user->id === $commentReport->user_id || $user->is_admin;
    }

    /**
     * Determine whether the user can restore the model.
     * Наприклад, тільки адміністратор може відновити звіт.
     */
    public function restore(User $user, CommentReport $commentReport): bool
    {
        return $user->is_admin; // Тільки адміністратор може відновлювати звіти
    }

    /**
     * Determine whether the user can permanently delete the model.
     * Наприклад, тільки адміністратор може остаточно видалити звіт.
     */
    public function forceDelete(User $user, CommentReport $commentReport): bool
    {
        return $user->is_admin; // Тільки адміністратор може видаляти звіти назавжди
    }
}

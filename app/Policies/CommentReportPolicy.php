<?php

namespace Liamtseva\Cinema\Policies;

use Liamtseva\Cinema\Models\CommentReport;
use Liamtseva\Cinema\Models\User;

class CommentReportPolicy
{
    public function before(User $user, $ability): ?bool
    {
        if ($user->isAdmin()) {
            return true; // Адміністратор має доступ до всіх дій
        }

        return null; // Продовжуємо перевірку інших методів
    }

    /**
     * Усі адміністратори можуть переглядати список звітів.
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdmin(); // Тільки адміністратор може переглядати всі звіти
    }

    /**
     * Користувач може переглядати звіт, якщо він його створив, або якщо він адміністратор.
     */
    public function view(User $user, CommentReport $commentReport): bool
    {
        return $user->id === $commentReport->user_id || $user->isAdmin();
    }

    /**
     * Будь-який авторизований користувач може створювати звіти.
     */
    public function create(User $user): bool
    {
        // Припускаємо, що авторизація перевіряється через існування $user
        return $user !== null; // Замість isAuthenticated(), якщо такого методу немає
    }

    /**
     * Тільки адміністратор може редагувати звіти.
     */
    public function update(User $user, CommentReport $commentReport): bool
    {
        return $user->isAdmin();
    }

    /**
     * Користувач може видалити звіт, якщо він його створив, або якщо він адміністратор.
     */
    public function delete(User $user, CommentReport $commentReport): bool
    {
        return $user->id === $commentReport->user_id || $user->isAdmin();
    }

    /**
     * Тільки адміністратор може відновлювати звіти.
     */
    public function restore(User $user, CommentReport $commentReport): bool
    {
        return $user->isAdmin();
    }

    /**
     * Тільки адміністратор може остаточно видаляти звіти.
     */
    public function forceDelete(User $user, CommentReport $commentReport): bool
    {
        return $user->isAdmin();
    }
}

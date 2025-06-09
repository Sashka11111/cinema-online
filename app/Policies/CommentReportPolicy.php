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

        // Модератори можуть переглядати та обробляти звіти
        if ($user->isModerator() && in_array($ability, ['viewAny', 'view', 'update'])) {
            return true;
        }

        return null; // Продовжуємо перевірку інших методів
    }

    /**
     * Усі адміністратори та модератори можуть переглядати список звітів.
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isModerator();
    }

    /**
     * Користувач може переглядати звіт, якщо він його створив, або якщо він адміністратор чи модератор.
     */
    public function view(User $user, CommentReport $commentReport): bool
    {
        return $user->id === $commentReport->user_id || $user->isAdmin() || $user->isModerator();
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
     * Тільки адміністратор або модератор може редагувати звіти.
     */
    public function update(User $user, CommentReport $commentReport): bool
    {
        return $user->isAdmin() || $user->isModerator();
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

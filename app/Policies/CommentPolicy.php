<?php

namespace Liamtseva\Cinema\Policies;

use Liamtseva\Cinema\Models\Comment;
use Liamtseva\Cinema\Models\User;

class CommentPolicy
{
    public function before(User $user, $ability): ?bool
    {
        // Якщо користувач адміністратор, дозволяємо всі дії
        if ($user->isAdmin()) {
            return true;
        }

        // Модератори можуть переглядати, редагувати та видаляти коментарі
        if ($user->isModerator() && in_array($ability, ['viewAny', 'view', 'update', 'delete'])) {
            return true;
        }

        return null;
    }

    /**
     * Determine whether the user can view any models.
     * Усі авторизовані користувачі можуть переглядати список коментарів.
     */
    public function viewAny(User $user): bool
    {
        return $user->isAuthenticated(); // Перевірка авторизації користувача
    }

    /**
     * Determine whether the user can view the model.
     * Усі авторизовані користувачі можуть переглядати коментар.
     */
    public function view(User $user, Comment $comment): bool
    {
        return $user->isAuthenticated(); // Усі авторизовані можуть переглядати коментарі
    }

    /**
     * Determine whether the user can create models.
     * Тільки авторизовані користувачі можуть створювати коментарі.
     */
    public function create(User $user): bool
    {
        return $user->isAuthenticated(); // Тільки авторизовані можуть створювати
    }

    /**
     * Determine whether the user can update the model.
     * Дозволяється, якщо користувач є адміністратором, модератором або автором коментаря.
     */
    public function update(User $user, Comment $comment): bool
    {
        return $user->isAdmin() || $user->isModerator() || $user->id === $comment->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     * Дозволяється, якщо користувач є адміністратором, модератором або автором коментаря.
     */
    public function delete(User $user, Comment $comment): bool
    {
        return $user->isAdmin() || $user->isModerator() || $user->id === $comment->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     * Тільки адміністратор може відновлювати коментарі.
     */
    public function restore(User $user, Comment $comment): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can permanently delete the model.
     * Тільки адміністратор може остаточно видаляти коментарі.
     */
    public function forceDelete(User $user, Comment $comment): bool
    {
        return $user->isAdmin();
    }
}

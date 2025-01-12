<?php

namespace Liamtseva\Cinema\Policies;

use Liamtseva\Cinema\Models\Comment;
use Liamtseva\Cinema\Models\User;

class CommentPolicy
{
    /**
     * Determine whether the user can view any models.
     * Наприклад, будь-який авторизований користувач може переглядати коментарі.
     */
    public function viewAny(User $user): bool
    {
        return true; // Усі користувачі можуть переглядати список коментарів
    }

    /**
     * Determine whether the user can view the model.
     * Наприклад, користувач може переглядати коментар, якщо він опублікований.
     */
    public function view(User $user, Comment $comment): bool
    {
        return $comment->is_published; // Дозволяємо переглядати лише опубліковані коментарі
    }

    /**
     * Determine whether the user can create models.
     * Наприклад, лише авторизовані користувачі можуть створювати коментарі.
     */
    public function create(User $user): bool
    {
        return $user->isAuthenticated(); // Припустимо, метод isAuthenticated() перевіряє авторизацію
    }

    /**
     * Determine whether the user can update the model.
     * Наприклад, користувач може редагувати тільки свої коментарі.
     */
    public function update(User $user, Comment $comment): bool
    {
        return $user->id === $comment->user_id; // Дозволяємо редагувати тільки свій коментар
    }

    /**
     * Determine whether the user can delete the model.
     * Наприклад, адмін або автор коментаря можуть видалити його.
     */
    public function delete(User $user, Comment $comment): bool
    {
        return $user->id === $comment->user_id || $user->is_admin; // Автор або адміністратор
    }

    /**
     * Determine whether the user can restore the model.
     * Наприклад, тільки адмін може відновити коментар.
     */
    public function restore(User $user, Comment $comment): bool
    {
        return $user->is_admin; // Тільки адміністратор може відновити коментар
    }

    /**
     * Determine whether the user can permanently delete the model.
     * Наприклад, тільки адмін може остаточно видалити коментар.
     */
    public function forceDelete(User $user, Comment $comment): bool
    {
        return $user->is_admin; // Тільки адміністратор може видаляти назавжди
    }
}

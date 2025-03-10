<?php

namespace Liamtseva\Cinema\Policies;

use Liamtseva\Cinema\Models\Tag;
use Liamtseva\Cinema\Models\User;

class TagPolicy
{
    public function before(?User $user, $ability): ?bool
    {
        // Якщо користувач адміністратор, дозволяємо всі дії
        if ($user && $user->isAdmin()) {
            return true;
        }

        return null; // Продовжуємо перевірку в інших методах
    }

    /**
     * Усі користувачі (включаючи неавторизованих) можуть переглядати список тегів.
     */
    public function viewAny(?User $user): bool
    {
        return true; // Теги зазвичай публічні, тому доступні всім
    }

    /**
     * Усі користувачі (включаючи неавторизованих) можуть переглядати окремий тег.
     */
    public function view(?User $user, Tag $tag): bool
    {
        return true; // Окремий тег також доступний для перегляду всім
    }

    /**
     * Тільки авторизовані користувачі можуть створювати нові теги.
     */
    public function create(User $user): bool
    {
        return $user !== null; // Дозволяємо створення тільки авторизованим
    }

    /**
     * Тільки адміністратори можуть оновлювати теги.
     */
    public function update(User $user, Tag $tag): bool
    {
        return $user->isAdmin();
    }

    /**
     * Тільки адміністратори можуть видаляти теги.
     */
    public function delete(User $user, Tag $tag): bool
    {
        return $user->isAdmin();
    }

    /**
     * Тільки адміністратори можуть відновлювати теги.
     */
    public function restore(User $user, Tag $tag): bool
    {
        return $user->isAdmin();
    }

    /**
     * Тільки адміністратори можуть остаточно видаляти теги.
     */
    public function forceDelete(User $user, Tag $tag): bool
    {
        return $user->isAdmin();
    }
}

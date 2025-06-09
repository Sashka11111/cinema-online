<?php

namespace Liamtseva\Cinema\Policies;

use Liamtseva\Cinema\Enums\Role;
use Liamtseva\Cinema\Models\User;
use Liamtseva\Cinema\Models\UserList;

class UserListPolicy
{
    public function before(User $user, string $ability): ?bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        // Модератори можуть переглядати списки користувачів
        if ($user->isModerator() && in_array($ability, ['viewAny', 'view'])) {
            return true;
        }

        return null;
    }

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, UserList $userList): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        // Дозволити створення тільки авторизованим користувачам
        return auth()->check();
    }

    public function update(User $user, UserList $userList): bool
    {
        // Дозволити оновлення, якщо користувач є власником списку
        return $user->id === $userList->user_id;
    }

    public function delete(User $user, UserList $userList): bool
    {
        return $user->id === $userList->user_id;
    }

    public function restore(User $user, UserList $userList): bool
    {
        return $user->id === $userList->user_id;
    }

    public function forceDelete(User $user, UserList $userList): bool
    {
        return $user->id === $userList->user_id;
    }
}

<?php

namespace Liamtseva\Cinema\Policies;

use Liamtseva\Cinema\Models\User;
use Liamtseva\Cinema\Models\UserList;

class UserListPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Дозволити доступ усім авторизованим користувачам
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, UserList $userList): bool
    {
        // Дозволити перегляд, якщо користувач є власником списку
        return $user->id === $userList->user_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Дозволити створення тільки авторизованим користувачам
        return $user->role === 'admin' || $user->role === 'user';
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, UserList $userList): bool
    {
        // Дозволити оновлення, якщо користувач є власником списку
        return $user->id === $userList->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, UserList $userList): bool
    {
        // Дозволити видалення, якщо користувач є власником списку
        return $user->id === $userList->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, UserList $userList): bool
    {
        // Дозволити відновлення, якщо користувач є власником списку
        return $user->id === $userList->user_id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, UserList $userList): bool
    {
        // Дозволити остаточне видалення, якщо користувач є власником списку
        return $user->id === $userList->user_id;
    }
}

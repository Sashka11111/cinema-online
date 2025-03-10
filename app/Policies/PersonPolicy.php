<?php

namespace Liamtseva\Cinema\Policies;

use Liamtseva\Cinema\Models\Person;
use Liamtseva\Cinema\Models\User;

class PersonPolicy
{
    public function before(User $user, $ability): ?bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return null;
    }

    /**
     * Усі користувачі (включаючи неавторизованих) можуть переглядати список осіб.
     */
    public function viewAny(?User $user): bool
    {
        return true;
    }

    /**
     * Усі користувачі (включаючи неавторизованих) можуть переглядати окремий запис.
     */
    public function view(?User $user, Person $person): bool
    {
        return true;
    }

    /**
     * Тільки адміністратор може створювати нові записи про осіб.
     */
    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Дозволяється, якщо користувач є адміністратором або власником запису.
     */
    public function update(User $user, Person $person): bool
    {
        return $user->isAdmin() || $user->id === $person->user_id;
    }

    /**
     * Тільки адміністратор може видаляти записи.
     */
    public function delete(User $user, Person $person): bool
    {
        return $user->isAdmin();
    }

    /**
     * Тільки адміністратор може відновлювати записи.
     */
    public function restore(User $user, Person $person): bool
    {
        return $user->isAdmin();
    }

    /**
     * Тільки адміністратор може остаточно видаляти записи.
     */
    public function forceDelete(User $user, Person $person): bool
    {
        return $user->isAdmin();
    }
}

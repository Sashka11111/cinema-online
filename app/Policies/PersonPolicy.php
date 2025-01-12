<?php

namespace Liamtseva\Cinema\Policies;

use Liamtseva\Cinema\Models\Person;
use Liamtseva\Cinema\Models\User;

class PersonPolicy
{
    /**
     * Determine whether the user can view any models.
     * Усі авторизовані користувачі можуть переглядати список осіб.
     */
    public function viewAny(User $user): bool
    {
        return $user->isAuthenticated(); // Перевіряємо, чи користувач авторизований
    }

    /**
     * Determine whether the user can view the model.
     * Дозволяється переглядати, якщо користувач є адміністратором
     * або самою особою, яку він переглядає.
     */
    public function view(User $user, Person $person): bool
    {
        return $user->is_admin || $user->id === $person->user_id;
    }

    /**
     * Determine whether the user can create models.
     * Тільки адміністратор може створювати нові записи про осіб.
     */
    public function create(User $user): bool
    {
        return $user->is_admin;
    }

    /**
     * Determine whether the user can update the model.
     * Дозволяється, якщо користувач є адміністратором
     * або власником запису про особу.
     */
    public function update(User $user, Person $person): bool
    {
        return $user->is_admin || $user->id === $person->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     * Тільки адміністратор може видаляти записи.
     */
    public function delete(User $user, Person $person): bool
    {
        return $user->is_admin;
    }

    /**
     * Determine whether the user can restore the model.
     * Тільки адміністратор може відновлювати записи.
     */
    public function restore(User $user, Person $person): bool
    {
        return $user->is_admin;
    }

    /**
     * Determine whether the user can permanently delete the model.
     * Тільки адміністратор може остаточно видаляти записи.
     */
    public function forceDelete(User $user, Person $person): bool
    {
        return $user->is_admin;
    }
}

<?php

namespace Liamtseva\Cinema\Policies;

use Liamtseva\Cinema\Models\Studio;
use Liamtseva\Cinema\Models\User;

class StudioPolicy
{
    public function before(User $user, $ability): ?bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        // Модератори можуть переглядати та редагувати студії
        if ($user->isModerator() && in_array($ability, ['viewAny', 'view', 'update', 'create'])) {
            return true;
        }

        return null;
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return auth()->check();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Studio $studio): bool
    {
        return $user->id === $studio->user_id || $user->isAdmin() || $user->isModerator();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isModerator();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Studio $studio): bool
    {
        return $user->id === $studio->user_id || $user->isAdmin() || $user->isModerator();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Studio $studio): bool
    {
        return $user->id === $studio->user_id || $user->isAdmin();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Studio $studio): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Studio $studio): bool
    {
        return $user->isAdmin();
    }
}

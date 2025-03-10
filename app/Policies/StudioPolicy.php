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

        return null;

    }

    public function viewAny(User $user): bool
    {
        // Example: Allow all authenticated users to view studios
        return auth()->check();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Studio $studio): bool
    {
        // Example: Allow the user to view the studio if they are the owner or an admin
        return $user->id === $studio->user_id || $user->is_admin;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Example: Only allow users with the "admin" role to create studios
        return $user->is_admin;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Studio $studio): bool
    {
        // Example: Allow the user to update the studio if they are the owner or an admin
        return $user->id === $studio->user_id || $user->is_admin;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Studio $studio): bool
    {
        // Example: Allow the user to delete the studio if they are the owner or an admin
        return $user->id === $studio->user_id || $user->is_admin;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Studio $studio): bool
    {
        // Example: Only allow admins to restore studios
        return $user->is_admin;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Studio $studio): bool
    {
        // Example: Only allow admins to permanently delete studios
        return $user->is_admin;
    }
}

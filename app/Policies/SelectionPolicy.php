<?php

namespace Liamtseva\Cinema\Policies;

use Liamtseva\Cinema\Models\Selection;
use Liamtseva\Cinema\Models\User;

class SelectionPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Example: Allow all authenticated users to view selections
        return $user->is_authenticated;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Selection $selection): bool
    {
        // Example: Allow the user to view the selection if they are the owner or an admin
        return $user->id === $selection->user_id || $user->is_admin;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Example: Allow any authenticated user to create a selection
        return $user->is_authenticated;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Selection $selection): bool
    {
        // Example: Allow the user to update the selection if they are the owner or an admin
        return $user->id === $selection->user_id || $user->is_admin;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Selection $selection): bool
    {
        // Example: Allow the user to delete the selection if they are the owner or an admin
        return $user->id === $selection->user_id || $user->is_admin;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Selection $selection): bool
    {
        // Example: Only allow admins to restore selections
        return $user->is_admin;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Selection $selection): bool
    {
        // Example: Only allow admins to permanently delete selections
        return $user->is_admin;
    }
}

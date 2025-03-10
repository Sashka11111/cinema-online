<?php

namespace Liamtseva\Cinema\Policies;

use Liamtseva\Cinema\Models\Episode;
use Liamtseva\Cinema\Models\User;

class EpisodePolicy
{
    public function before(User $user, $ability): ?bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return null;
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Allow all authenticated users to view episodes list
        return auth()->check();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Episode $episode): bool
    {
        // Allow viewing public episodes or if user is admin
        return $episode->is_public || $user->isAdmin();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Only allow admins to create episodes
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Episode $episode): bool
    {
        // Allow the user to update the episode if they are the owner or an admin
        return $user->id === $episode->user_id || $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Episode $episode): bool
    {
        // Only allow admins to delete episodes
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Episode $episode): bool
    {
        // Only allow admins to restore episodes
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Episode $episode): bool
    {
        // Only allow admins to permanently delete episodes
        return $user->isAdmin();
    }
}

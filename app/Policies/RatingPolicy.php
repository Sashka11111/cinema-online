<?php

namespace Liamtseva\Cinema\Policies;

use Liamtseva\Cinema\Models\Rating;
use Liamtseva\Cinema\Models\User;

class RatingPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Example: Allow all users to view ratings
        return true; // or add your own logic here
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Rating $rating): bool
    {
        // Example: Only allow the owner or an admin to view the rating
        return $user->id === $rating->user_id || $user->is_admin;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Example: Allow any authenticated user to create ratings
        return $user->is_authenticated;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Rating $rating): bool
    {
        // Example: Only allow the owner or an admin to update the rating
        return $user->id === $rating->user_id || $user->is_admin;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Rating $rating): bool
    {
        // Example: Only allow the owner or an admin to delete the rating
        return $user->id === $rating->user_id || $user->is_admin;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Rating $rating): bool
    {
        // Example: Only an admin can restore ratings
        return $user->is_admin;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Rating $rating): bool
    {
        // Example: Only an admin can permanently delete ratings
        return $user->is_admin;
    }
}

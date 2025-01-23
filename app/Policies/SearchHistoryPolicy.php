<?php

namespace Liamtseva\Cinema\Policies;

use Liamtseva\Cinema\Models\SearchHistory;
use Liamtseva\Cinema\Models\User;

class SearchHistoryPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Example: Allow all authenticated users to view search history
        return auth()->check();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, SearchHistory $searchHistory): bool
    {
        // Example: Allow the user to view their own search history or if the user is an admin
        return $user->id === $searchHistory->user_id || $user->is_admin;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Example: Allow any authenticated user to create search history records
        return auth()->check();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, SearchHistory $searchHistory): bool
    {
        // Example: Only allow the user to update their own search history or if the user is an admin
        return $user->id === $searchHistory->user_id || $user->is_admin;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, SearchHistory $searchHistory): bool
    {
        // Example: Only allow the user to delete their own search history or if the user is an admin
        return $user->id === $searchHistory->user_id || $user->is_admin;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, SearchHistory $searchHistory): bool
    {
        // Example: Only allow admins to restore search history
        return $user->is_admin;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, SearchHistory $searchHistory): bool
    {
        // Example: Only allow admins to permanently delete search history
        return $user->is_admin;
    }
}

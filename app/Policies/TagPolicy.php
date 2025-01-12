<?php

namespace Liamtseva\Cinema\Policies;

use Liamtseva\Cinema\Models\Tag;
use Liamtseva\Cinema\Models\User;

class TagPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Example: Allow all authenticated users to view tags
        return auth()->check();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Tag $tag): bool
    {
        // Example: Allow users to view a tag if they have permission or if they are admins
        return $user->is_admin || $user->hasPermissionTo('view_tags');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Example: Allow users with "create_tags" permission to create tags
        return $user->hasPermissionTo('create_tags');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Tag $tag): bool
    {
        // Example: Allow users to update a tag if they have the permission or are admins
        return $user->is_admin || $user->hasPermissionTo('update_tags');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Tag $tag): bool
    {
        // Example: Allow users to delete a tag if they have the permission or are admins
        return $user->is_admin || $user->hasPermissionTo('delete_tags');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Tag $tag): bool
    {
        // Example: Only allow admins to restore tags
        return $user->is_admin;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Tag $tag): bool
    {
        // Example: Only allow admins to permanently delete tags
        return $user->is_admin;
    }
}

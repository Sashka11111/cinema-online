<?php

namespace Liamtseva\Cinema\Policies;

use Liamtseva\Cinema\Models\CommentLike;
use Liamtseva\Cinema\Models\User;

class CommentLikePolicy
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
        return true; // Усі авторизовані користувачі можуть переглядати
    }

    public function view(User $user, CommentLike $commentLike): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->exists; // Усі авторизовані користувачі можуть створювати
    }

    public function update(User $user, CommentLike $commentLike): bool
    {
        return $user->isAdmin() || $user->id === $commentLike->user_id;
    }

    public function delete(User $user, CommentLike $commentLike): bool
    {
        return $user->isAdmin() || $user->id === $commentLike->user_id;
    }

    public function restore(User $user, CommentLike $commentLike): bool
    {
        return $user->isAdmin();
    }

    public function forceDelete(User $user, CommentLike $commentLike): bool
    {
        return $user->isAdmin();
    }
}

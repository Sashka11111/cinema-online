<?php

namespace Liamtseva\Cinema\Policies;

use Liamtseva\Cinema\Models\User;
use Liamtseva\Cinema\Models\WatchHistory;

class WatchHistoryPolicy
{
    public function before(User $user, $ability): ?bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        // Модератори можуть переглядати історію переглядів
        if ($user->isModerator() && in_array($ability, ['viewAny', 'view'])) {
            return true;
        }

        return null;
    }

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, WatchHistory $watchHistory): bool
    {
        return $user->id === $watchHistory->user_id || $user->isAdmin() || $user->isModerator();
    }

    public function delete(User $user, WatchHistory $watchHistory): bool
    {
        return $user->id === $watchHistory->user_id || $user->isAdmin();
    }
}

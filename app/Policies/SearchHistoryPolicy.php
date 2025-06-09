<?php

namespace Liamtseva\Cinema\Policies;

use Liamtseva\Cinema\Models\SearchHistory;
use Liamtseva\Cinema\Models\User;

class SearchHistoryPolicy
{
    public function before(User $user, $ability): ?bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        // Модератори можуть переглядати історію пошуку
        if ($user->isModerator() && in_array($ability, ['viewAny', 'view'])) {
            return true;
        }

        return null;
    }

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, SearchHistory $searchHistory): bool
    {
        return $user->id === $searchHistory->user_id || $user->isAdmin() || $user->isModerator();
    }

    public function delete(User $user, SearchHistory $searchHistory): bool
    {
        return $user->id === $searchHistory->user_id || $user->isAdmin();
    }
}

<?php

namespace Liamtseva\Cinema\Policies;

use Liamtseva\Cinema\Models\CommentLike;
use Liamtseva\Cinema\Models\User;

class CommentLikePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->exists; // Усі авторизовані користувачі можуть переглядати
    }

    public function view(User $user, CommentLike $commentLike): bool
    {
        return $user->id === $commentLike->user_id; // Тільки власник лайка може переглядати
    }

    public function create(User $user): bool
    {
        return $user->exists; // Усі авторизовані користувачі можуть створювати
    }

    public function update(User $user, CommentLike $commentLike): bool
    {
        return $user->id === $commentLike->user_id; // Тільки власник може змінювати лайк
    }

    public function delete(User $user, CommentLike $commentLike): bool
    {
        return $user->role === 'admin' || $user->id === $commentLike->user_id;
    }

    public function restore(User $user, CommentLike $commentLike): bool
    {
        return $user->role === 'admin'; // Тільки адміністратор може відновити
    }

    public function forceDelete(User $user, CommentLike $commentLike): bool
    {
        return $user->role === 'admin'; // Тільки адміністратор може остаточно видалити
    }
}

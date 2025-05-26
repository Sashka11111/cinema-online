<?php

use Illuminate\Support\Facades\Broadcast;
use Liamtseva\Cinema\Models\Room;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// Канал для кімнат - тільки учасники кімнати можуть слухати
Broadcast::channel('room.{roomSlug}', function ($user, $roomSlug) {
    $room = Room::where('slug', $roomSlug)->first();

    if (! $room || ! $room->isActive()) {
        return false;
    }

    // Перевіряємо, чи користувач є учасником кімнати
    return $room->viewers()
        ->wherePivot('user_id', $user->id)
        ->wherePivot('left_at', null)
        ->exists();
});

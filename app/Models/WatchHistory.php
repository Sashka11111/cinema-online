<?php

namespace Liamtseva\Cinema\Models;

use Database\Factories\WatchHistoryFactory;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperWatchHistory
 */
class WatchHistory extends Model
{
    /** @use HasFactory<WatchHistoryFactory> */
    use HasFactory, HasUlids;

    public static function cleanOldHistory(int $userId, int $days = 30)
    {
        // Очищаємо історію перегляду для користувача, якщо вона старша ніж задані дні
        return self::where('user_id', $userId)
            ->where('created_at', '<', now()->subDays($days))
            ->delete();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function episode(): BelongsTo
    {
        return $this->belongsTo(Episode::class);
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeForEpisode($query, $episodeId)
    {
        return $query->where('episode_id', $episodeId);
    }
}

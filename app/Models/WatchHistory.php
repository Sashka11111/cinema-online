<?php

namespace Liamtseva\Cinema\Models;

use Database\Factories\WatchHistoryFactory;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Liamtseva\Cinema\Models\Builders\WatchHistoryQueryBuilder;

/**
 * @mixin IdeHelperWatchHistory
 */
class WatchHistory extends Model
{
    /** @use HasFactory<WatchHistoryFactory> */
    use HasFactory, HasUlids;

    public function newEloquentBuilder($query): WatchHistoryQueryBuilder
    {
        return new WatchHistoryQueryBuilder($query);
    }

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
}

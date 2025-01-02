<?php

namespace Liamtseva\Cinema\Models;

use Database\Factories\SearchHistoryFactory;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperSearchHistory
 */
class SearchHistory extends Model
{
    /** @use HasFactory<SearchHistoryFactory> */
    use HasFactory, HasUlids;

    public static function cleanOldHistory(int $userId, int $days = 30)
    {
        return self::where('user_id', $userId)
            ->where('created_at', '<', now()->subDays($days))
            ->delete();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

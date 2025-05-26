<?php

namespace Liamtseva\Cinema\Models;

use Database\Factories\TagFactory;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;
use Liamtseva\Cinema\Enums\RoomStatus;
use Liamtseva\Cinema\Models\Builders\RoomQueryBuilder;
use Liamtseva\Cinema\Models\Traits\HasSeo;

/**
 * @mixin IdeHelperRoom
 */
class Room extends Model
{
    /** @use HasFactory<TagFactory> */
    use HasFactory, HasSeo, HasUlids;

    protected $fillable = [
        'id',
        'name',
        'slug',
        'user_id',
        'episode_id',
        'is_private',
        'room_status',
        'password',
        'max_viewers',
        'started_at',
        'ended_at',
    ];

    protected $casts = [
        'is_private' => 'boolean',
        'room_status' => RoomStatus::class,
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
    ];

    /**
     * Bootstrap the model and its traits.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (! $model->id) {
                $model->id = (string) Str::ulid();
            }
        });
    }

    /**
     * Повертає новий екземпляр білдера запитів для моделі.
     */
    public function newEloquentBuilder($query): RoomQueryBuilder
    {
        return new RoomQueryBuilder($query);
    }

    /**
     * Отримати власника кімнати.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Отримати епізод, який переглядається в кімнаті.
     */
    public function episode(): BelongsTo
    {
        return $this->belongsTo(Episode::class);
    }

    /**
     * Отримати глядачів кімнати.
     */
    public function viewers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'room_user')
            ->withPivot('joined_at', 'left_at')
            ->withTimestamps();
    }

    /**
     * Отримати активних глядачів кімнати.
     */
    public function activeViewers(): BelongsToMany
    {
        return $this->viewers()->whereNull('room_user.left_at');
    }

    /**
     * Перевірити, чи кімната активна.
     */
    public function isActive(): bool
    {
        return $this->room_status === RoomStatus::ACTIVE;
    }

    /**
     * Перевірити, чи кімната завершена.
     */
    public function isCompleted(): bool
    {
        return $this->room_status === RoomStatus::COMPLETED;
    }

    /**
     * Перевірити, чи кімната ще не розпочата.
     */
    public function isNotStarted(): bool
    {
        return $this->room_status === RoomStatus::NOT_STARTED;
    }

    /**
     * Розпочати перегляд у кімнаті.
     */
    public function start(): self
    {
        $this->update([
            'room_status' => RoomStatus::ACTIVE,
            'started_at' => now(),
        ]);

        return $this;
    }

    /**
     * Завершити перегляд у кімнаті.
     */
    public function end(): self
    {
        $this->update([
            'room_status' => RoomStatus::COMPLETED,
            'ended_at' => now(),
        ]);

        return $this;
    }

    /**
     * Отримати кількість активних глядачів.
     */
    public function getActiveViewersCount(): int
    {
        return $this->activeViewers()->count();
    }

    /**
     * Перевірити, чи кімната заповнена.
     */
    public function isFull(): bool
    {
        return $this->getActiveViewersCount() >= $this->max_viewers;
    }
}

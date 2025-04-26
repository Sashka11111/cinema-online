<?php

namespace Liamtseva\Cinema\Models;

use Database\Factories\UserFactory;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Liamtseva\Cinema\Enums\Gender;
use Liamtseva\Cinema\Enums\Role;
use Liamtseva\Cinema\Enums\UserListType;
use Liamtseva\Cinema\Models\Builders\UserQueryBuilder;

/**
 * @mixin IdeHelperUser
 */
class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, HasUlids, Notifiable;

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'role' => Role::class,
        'gender' => Gender::class,
        'email_verified_at' => 'datetime',
        'birthday' => 'date',
        'password' => 'hashed',
    ];

    public function newEloquentBuilder($query): UserQueryBuilder
    {
        return new UserQueryBuilder($query);
    }

    public function ratings(): HasMany
    {
        return $this->hasMany(Rating::class)->chaperone();
    }

    public function movieNotifications(): BelongsToMany
    {
        return $this->belongsToMany(Movie::class, 'movie_user_notifications')
            ->withTimestamps();
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class)->chaperone();
    }

    public function commentLikes(): HasMany
    {
        return $this->hasMany(CommentLike::class)->chaperone();
    }

    public function commentReports(): HasMany
    {
        return $this->hasMany(CommentReport::class)->chaperone();
    }

    public function searchHistories(): HasMany
    {
        return $this->hasMany(SearchHistory::class)->chaperone();
    }

    public function watchHistories(): HasMany
    {
        return $this->hasMany(WatchHistory::class)->chaperone();
    }

    public function selections(): HasMany
    {
        return $this->HasMany(Selection::class)->chaperone();
    }

    public function favoriteMovies(): HasMany
    {
        return $this->userLists()
            ->where('listable_type', Movie::class)
            ->where('type', UserListType::FAVORITE->value);
    }

    public function userLists(): HasMany
    {
        return $this->hasMany(UserList::class);
    }

    /**
     * Get all subscriptions for the user.
     *
     * @return HasMany
     */
    public function subscriptions(): HasMany
    {
        return $this->hasMany(UserSubscription::class)->chaperone();
    }

    /**
     * Get all payments made by the user.
     *
     * @return HasMany
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class)->chaperone();
    }

    /**
     * Get the active subscription for the user.
     *
     * @return UserSubscription|null
     */
    public function activeSubscription()
    {
        return $this->subscriptions()
            ->where('is_active', true)
            ->where('end_date', '>', now())
            ->latest('end_date')
            ->first();
    }

    /**
     * Check if the user has an active subscription.
     *
     * @return bool
     */
    public function hasActiveSubscription(): bool
    {
        return $this->activeSubscription() !== null;
    }

    public function favoritePeople(): HasMany
    {
        return $this->userLists()
            ->where('listable_type', Person::class)
            ->where('type', UserListType::FAVORITE->value);
    }

    public function favoriteTags(): HasMany
    {
        return $this->userLists()
            ->where('listable_type', Tag::class)
            ->where('type', UserListType::FAVORITE->value);
    }

    public function favoriteEpisodes(): HasMany
    {
        return $this->userLists()
            ->where('listable_type', Person::class)
            ->where('type', UserListType::FAVORITE->value);
    }

    public function watchingMovies(): HasMany
    {
        return $this->userLists()
            ->where('listable_type', Movie::class)
            ->where('type', UserListType::WATCHING->value);
    }

    public function plannedMovies(): HasMany
    {
        return $this->userLists()
            ->where('listable_type', Movie::class)
            ->where('type', UserListType::PLANNED->value);
    }

    public function watchedMovies(): HasMany
    {
        return $this->userLists()
            ->where('listable_type', Movie::class)
            ->where('type', UserListType::WATCHED->value);
    }

    public function stoppedMovies(): HasMany
    {
        return $this->userLists()
            ->where('listable_type', Movie::class)
            ->where('type', UserListType::STOPPED->value);
    }

    public function reWatchingMovies(): HasMany
    {
        return $this->userLists()
            ->where('listable_type', Movie::class)
            ->where('type', UserListType::REWATCHING->value);
    }

    public function isAuthenticated(): bool
    {
        return auth()->check();  // Перевіряє, чи користувач аутентифікований
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->isAdmin();
    }

    public function isAdmin(): bool
    {
        return $this->role == Role::ADMIN;
    }

    protected function avatar(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? asset("storage/$value") : null
        );
    }
}

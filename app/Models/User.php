<?php

namespace Liamtseva\Cinema\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Liamtseva\Cinema\Enums\Gender;
use Liamtseva\Cinema\Enums\Role;
use Liamtseva\Cinema\Enums\UserListType;

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

    public function scopeAllowedAdults(Builder $query): Builder
    {
        return $query->where('allow_adult', true);
    }

    public function scopeByRole(Builder $query, Role $role): Builder
    {
        return $query->where('role', $role->value);
    }

    public function scopeIsAdmin(Builder $query): Builder
    {
        return $query->where('role', Role::ADMIN->value);
    }

    public function scopeVipCustomer(Builder $query): Builder
    {
        return $query->where('vip', true);
    }

    public function ratings(): HasMany
    {
        return $this->hasMany(Rating::class)->chaperone();
    }

    public function movieNotifications()
    {
        return $this->belongsToMany(Movie::class, 'movie_user_notifications')
            ->as('notification')
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

    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }

    public function isAdmin(): bool
    {
        return $this->role == Role::ADMIN;
    }

    // TODO: отримати реальний шлях до картинки

    protected function casts(): array
    {
        return [
            'role' => Role::class,
            'gender' => Gender::class,
            'email_verified_at' => 'datetime',
            'birthday' => 'date',
            'password' => 'hashed',
        ];
    }

    protected function avatar(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? asset("storage/$value") : null
        );
    }
}

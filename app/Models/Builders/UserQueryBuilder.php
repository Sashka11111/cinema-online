<?php

namespace Liamtseva\Cinema\Models\Builders;

use Illuminate\Database\Eloquent\Builder;
use Liamtseva\Cinema\Enums\Role;

class UserQueryBuilder extends Builder
{
    /**
     * Filter users who are allowed to see adult content.
     *
     * @return $this
     */
    public function allowedAdults(): self
    {
        return $this->where('allow_adult', true);
    }

    /**
     * Filter users by role.
     *
     * @param Role $role
     * @return $this
     */
    public function byRole(Role $role): self
    {
        return $this->where('role', $role->value);
    }

    /**
     * Filter admin users.
     *
     * @return $this
     */
    public function isAdmin(): self
    {
        return $this->where('role', Role::ADMIN->value);
    }

    /**
     * Filter VIP users.
     *
     * @return $this
     */
    public function vipCustomer(): self
    {
        return $this->where('vip', true);
    }

    /**
     * Filter users by email.
     *
     * @param string $email
     * @return $this
     */
    public function byEmail(string $email): self
    {
        return $this->where('email', $email);
    }

    /**
     * Filter users by name.
     *
     * @param string $name
     * @return $this
     */
    public function byName(string $name): self
    {
        return $this->where('name', 'like', "%{$name}%");
    }

    /**
     * Filter users who have verified their email.
     *
     * @return $this
     */
    public function verified(): self
    {
        return $this->whereNotNull('email_verified_at');
    }

    /**
     * Filter users who have not verified their email.
     *
     * @return $this
     */
    public function unverified(): self
    {
        return $this->whereNull('email_verified_at');
    }

    /**
     * Filter users who have been active recently.
     *
     * @param int $days
     * @return $this
     */
    public function activeRecently(int $days = 7): self
    {
        return $this->where('last_seen_at', '>=', now()->subDays($days));
    }

    /**
     * Filter users who have not been active recently.
     *
     * @param int $days
     * @return $this
     */
    public function inactiveRecently(int $days = 30): self
    {
        return $this->where('last_seen_at', '<', now()->subDays($days));
    }

    /**
     * Filter users by age range.
     *
     * @param int $minAge
     * @param int $maxAge
     * @return $this
     */
    public function byAgeRange(int $minAge, int $maxAge): self
    {
        $minDate = now()->subYears($maxAge)->format('Y-m-d');
        $maxDate = now()->subYears($minAge)->format('Y-m-d');

        return $this->whereBetween('birthday', [$minDate, $maxDate]);
    }

    /**
     * Filter users who have auto-next enabled.
     *
     * @return $this
     */
    public function withAutoNext(): self
    {
        return $this->where('is_auto_next', true);
    }

    /**
     * Filter users who have auto-play enabled.
     *
     * @return $this
     */
    public function withAutoPlay(): self
    {
        return $this->where('is_auto_play', true);
    }
}

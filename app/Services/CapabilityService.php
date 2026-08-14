<?php

namespace App\Services;

use App\Models\Role;
use App\Models\User;

class CapabilityService
{
    /**
     * Effective capabilities resolved per user for the current request.
     *
     * @var array<int, array<int, string>>
     */
    private array $cache = [];

    /**
     * The capabilities granted to a user across all of their roles.
     *
     * @return array<int, string>
     */
    public function allFor(User $user): array
    {
        if (isset($this->cache[$user->id])) {
            return $this->cache[$user->id];
        }

        $capabilities = $user->roles
            ->flatMap(fn (Role $role) => $role->capabilities)
            ->all();

        // Every user can read, mirroring WordPress behavior for role-less users.
        if ($capabilities === []) {
            $capabilities = ['read'];
        }

        return $this->cache[$user->id] = array_values(array_unique($capabilities));
    }

    /**
     * Determine whether a user holds a given capability.
     */
    public function can(string $capability, ?User $user): bool
    {
        if ($user === null) {
            return false;
        }

        if ($this->hasRole($user, 'administrator')) {
            return true;
        }

        return in_array($capability, $this->allFor($user), true);
    }

    /**
     * Determine whether a user holds the given role.
     */
    public function hasRole(User $user, string $slug): bool
    {
        return $user->roles->contains(fn (Role $role) => $role->slug === $slug);
    }
}

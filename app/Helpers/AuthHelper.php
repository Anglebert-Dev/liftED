<?php

namespace App\Helpers;

use Illuminate\Http\Exceptions\HttpResponseException;

class AuthHelper
{
    /**
     * Check if the authenticated user has a permission.
     * Supports pipe-separated OR logic: "create programs.program|update programs.program"
     */
    public static function can(string $permission): bool
    {
        if (! auth()->check()) {
            return false;
        }

        $user = auth()->user();

        $permissions = explode('|', $permission);

        if ($user->hasRole('SuperAdmin')) {
            foreach ($permissions as $perm) {
                $name = trim($perm);
                if (in_array($name, self::superAdminDeniedPermissions(), true)) {
                    continue;
                }

                return true;
            }

            return false;
        }

        foreach ($permissions as $perm) {
            if ($user->hasPermissionTo(trim($perm))) {
                return true;
            }
        }

        return false;
    }

    /**
     * SuperAdmin bypass must not grant learner-only permissions.
     *
     * @return list<string>
     */
    public static function superAdminDeniedPermissions(): array
    {
        return ['read learners.own_progress'];
    }

    /**
     * Require a permission — throws HttpResponseException if user lacks it.
     */
    public static function require(string $permission): void
    {
        if (! static::can($permission)) {
            throw new HttpResponseException(
                redirect()->back()->withErrors(['error' => 'You do not have permission to perform this action.'])
            );
        }
    }

    /**
     * Shorthand alias.
     */
    public static function user()
    {
        return auth()->user();
    }
}

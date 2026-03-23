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

        // SuperAdmin has everything
        if ($user->hasRole('SuperAdmin')) {
            return true;
        }

        $permissions = explode('|', $permission);

        foreach ($permissions as $perm) {
            if ($user->hasPermissionTo(trim($perm))) {
                return true;
            }
        }

        return false;
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

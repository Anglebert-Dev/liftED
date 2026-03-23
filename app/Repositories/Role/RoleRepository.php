<?php

namespace App\Repositories\Role;

use Illuminate\Database\Eloquent\Collection;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleRepository
{
    public function getAll(): Collection
    {
        return Role::withCount('users')
            ->with('permissions')
            ->where('guard_name', 'web')
            ->orderBy('name')
            ->get();
    }

    public function findById(int $id): Role
    {
        return Role::with('permissions')->findOrFail($id);
    }

    public function getAllPermissionsGrouped(): array
    {
        $permissions = Permission::where('guard_name', 'web')->orderBy('name')->get();

        $grouped = [];

        foreach ($permissions as $permission) {
            $parts = explode(' ', $permission->name, 2);

            if (count($parts) === 2) {
                $moduleParts = explode('.', $parts[1], 2);
                $module      = $moduleParts[0];
                $controller  = $moduleParts[1] ?? 'general';
            } else {
                $module     = 'other';
                $controller = 'general';
            }

            $grouped[$module][$controller][] = $permission;
        }

        ksort($grouped);
        foreach ($grouped as &$controllers) {
            ksort($controllers);
        }

        return $grouped;
    }
}

<?php

namespace App\Services\Permission;

use App\Services\BaseService;
use Illuminate\Support\Facades\Cache;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionService extends BaseService
{
    /**
     * Generate all permissions from config/access.php.
     * Called once on first login (or forced with $force = true).
     */
    public function initPermissions(bool $force = false): void
    {
        $config     = config('access');
        $configHash = md5(serialize($config));
        $cacheKey   = 'permissions_seeded_' . $configHash;

        if (! $force && Cache::has($cacheKey)) {
            return;
        }

        // Use a lock to prevent race conditions on concurrent first-logins
        Cache::lock('permission_init_lock', 30)->block(10, function () use ($config, $cacheKey) {

            $allPermissions = $this->buildPermissionNames($config);

            // Insert only new permissions
            foreach ($allPermissions as $name) {
                Permission::firstOrCreate(
                    ['name' => $name, 'guard_name' => 'web']
                );
            }

            // Ensure SuperAdmin role exists and has every permission
            $superAdmin = Role::firstOrCreate(
                ['name' => 'SuperAdmin', 'guard_name' => 'web']
            );
            $superAdmin->syncPermissions(Permission::all());

            Cache::forever($cacheKey, true);
        });
    }

    private function buildPermissionNames(array $config): array
    {
        $names   = [];
        $modules = $config['access_modules'] ?? [];
        $actions = $config['access_actions'] ?? [];

        foreach ($modules as $module => $controllers) {
            foreach ($controllers as $controller) {
                foreach ($actions as $action) {
                    $names[] = "{$action} {$module}.{$controller}";
                }
            }
        }

        foreach ($config['extra_permissions'] ?? [] as $extra) {
            $names[] = $extra;
        }

        return $names;
    }
}

<?php

namespace App\Services\Role;

use App\Services\BaseService;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RoleService extends BaseService
{
    public function save(Request $request, ?Role $role = null): array
    {
        try {
            $isNew = is_null($role);

            if ($isNew) {
                $role = Role::create([
                    'name'       => $request->input('name'),
                    'guard_name' => 'web',
                ]);
            } else {
                $role->update(['name' => $request->input('name')]);
            }

            $role->syncPermissions($request->input('permissions', []));

            return $this->success($role, $isNew ? 'Role created.' : 'Role updated.');
        } catch (\Throwable $e) {
            return $this->failure('Could not save role: ' . $e->getMessage());
        }
    }

    public function delete(Role $role): array
    {
        try {
            if ($role->name === 'SuperAdmin') {
                return $this->failure('The SuperAdmin role cannot be deleted.');
            }

            $role->delete();

            return $this->success(null, 'Role deleted.');
        } catch (\Throwable $e) {
            return $this->failure('Could not delete role.');
        }
    }
}

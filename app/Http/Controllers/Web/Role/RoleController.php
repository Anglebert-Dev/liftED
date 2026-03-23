<?php

namespace App\Http\Controllers\Web\Role;

use App\Helpers\AuthHelper as A;
use App\Http\Controllers\Controller;
use App\Http\Requests\Role\SaveRoleRequest;
use App\Repositories\Role\RoleRepository;
use App\Services\Role\RoleService;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function __construct(
        private RoleService    $service,
        private RoleRepository $repo,
    ) {}

    public function index()
    {
        A::require('list users.role');
        $roles = $this->repo->getAll();
        return view('role.index', compact('roles'));
    }

    public function create()
    {
        A::require('create users.role');
        $permissions = $this->repo->getAllPermissionsGrouped();
        return view('role.edit', ['role' => null, 'permissions' => $permissions]);
    }

    public function store(SaveRoleRequest $request)
    {
        A::require('create users.role');
        $result = $this->service->save($request);

        if (! $result['status']) {
            return $this->errorRedirect($result['message']);
        }

        return $this->successRedirect('roles.index', $result['message']);
    }

    public function edit(Role $role)
    {
        A::require('update users.role');
        $permissions = $this->repo->getAllPermissionsGrouped();
        return view('role.edit', compact('role', 'permissions'));
    }

    public function update(SaveRoleRequest $request, Role $role)
    {
        A::require('update users.role');
        $result = $this->service->save($request, $role);

        if (! $result['status']) {
            return $this->errorRedirect($result['message']);
        }

        return $this->successRedirect('roles.index', $result['message']);
    }

    public function destroy(Role $role)
    {
        A::require('delete users.role');
        $result = $this->service->delete($role);

        if (! $result['status']) {
            return $this->errorRedirect($result['message']);
        }

        return $this->successRedirect('roles.index', $result['message']);
    }
}

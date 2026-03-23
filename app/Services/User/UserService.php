<?php

namespace App\Services\User;

use App\Models\User;
use App\Repositories\User\UserRepository;
use App\Services\BaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserService extends BaseService
{
    public function __construct(private UserRepository $repo) {}

    public function save(Request $request, ?User $user = null): array
    {
        try {
            $isNew = is_null($user);
            $user  = $user ?? new User();

            $user->fill([
                'firstname'    => $request->input('firstname'),
                'lastname'     => $request->input('lastname'),
                'email'        => $request->input('email'),
                'phone_number' => $request->input('phone_number'),
                'role'         => $request->input('role'),
                'ngo_id'       => $request->input('ngo_id'),
                'is_approved'  => $request->boolean('is_approved', false),
            ]);

            if ($isNew || $request->filled('password')) {
                $user->password = Hash::make($request->input('password'));
            }

            $this->repo->save($user);

            // Sync Spatie role
            $roleName = $this->spatieRole($user->role);
            $role     = Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
            $user->syncRoles([$roleName]);

            return $this->success($user, $isNew ? 'User created.' : 'User updated.');
        } catch (\Throwable $e) {
            return $this->failure('Could not save user: ' . $e->getMessage());
        }
    }

    public function ban(User $user): array
    {
        $user->banned_at = now();
        $this->repo->save($user);
        return $this->success(null, 'User banned.');
    }

    public function unban(User $user): array
    {
        $user->banned_at = null;
        $this->repo->save($user);
        return $this->success(null, 'User unbanned.');
    }

    public function approve(User $user): array
    {
        $user->is_approved = true;
        $this->repo->save($user);
        return $this->success(null, 'User approved.');
    }

    private function spatieRole(string $role): string
    {
        return match ($role) {
            'superadmin' => 'SuperAdmin',
            'ngo_staff'  => 'NGO Staff',
            'mentor'     => 'Mentor',
            'learner'    => 'Learner',
            default      => 'Learner',
        };
    }
}

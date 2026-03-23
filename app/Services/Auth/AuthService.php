<?php

namespace App\Services\Auth;

use App\Repositories\User\UserRepository;
use App\Services\BaseService;
use App\Services\Permission\PermissionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthService extends BaseService
{
    public function __construct(
        private UserRepository    $userRepo,
        private PermissionService $permissionService,
    ) {}

    public function login(Request $request): array
    {
        $credentials = $request->only('email', 'password');

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return $this->failure('Invalid email or password.');
        }

        $user = Auth::user();

        if ($user->isBanned()) {
            Auth::logout();
            return $this->failure('Your account has been suspended. Please contact support.');
        }

        if (! $user->isApproved()) {
            Auth::logout();
            return $this->failure('Your account is pending approval.');
        }

        $this->permissionService->initPermissions();

        $request->session()->regenerate();

        return $this->success([
            'redirect' => $this->getDashboardRoute($user->role),
        ], 'Welcome back, ' . $user->firstname . '!');
    }

    public function logout(Request $request): void
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }

    public function getDashboardRoute(string $role): string
    {
        return match ($role) {
            'superadmin' => route('dashboard.superadmin'),
            'ngo_staff'  => route('dashboard.ngo_staff'),
            'mentor'     => route('dashboard.mentor'),
            'learner'    => route('dashboard.learner'),
            default      => route('dashboard.learner'),
        };
    }
}

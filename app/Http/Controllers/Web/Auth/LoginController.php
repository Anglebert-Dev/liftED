<?php

namespace App\Http\Controllers\Web\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\Auth\AuthService;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function __construct(private AuthService $authService) {}

    public function showLogin()
    {
        if (auth()->check()) {
            return redirect($this->authService->getDashboardRoute(auth()->user()->role));
        }
        return view('auth.login');
    }

    public function login(LoginRequest $request)
    {
        $result = $this->authService->login($request);

        if (! $result['status']) {
            return back()->withErrors(['email' => $result['message']])->withInput($request->only('email'));
        }

        return redirect($result['data']['redirect']);
    }

    public function logout(Request $request)
    {
        $this->authService->logout($request);
        return redirect()->route('login');
    }
}

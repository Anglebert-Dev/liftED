<?php

namespace App\Http\Controllers\Web\User;

use App\Helpers\AuthHelper as A;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\SaveUserRequest;
use App\Models\User;
use App\Repositories\User\UserRepository;
use App\Services\User\UserService;

class UserController extends Controller
{
    public function __construct(
        private UserService    $service,
        private UserRepository $repo,
    ) {}

    public function index()
    {
        A::require('list users.user');
        $users = $this->repo->getAllPaginated();
        return view('user.index', compact('users'));
    }

    public function show(User $user)
    {
        A::require('read users.user');
        return view('user.show', compact('user'));
    }

    public function create()
    {
        A::require('create users.user');
        return view('user.edit', ['user' => null]);
    }

    public function store(SaveUserRequest $request)
    {
        A::require('create users.user');
        $result = $this->service->save($request);

        if (! $result['status']) {
            return $this->errorRedirect($result['message']);
        }

        return $this->successRedirect('users.index', $result['message']);
    }

    public function edit(User $user)
    {
        A::require('update users.user');
        return view('user.edit', compact('user'));
    }

    public function update(SaveUserRequest $request, User $user)
    {
        A::require('update users.user');
        $result = $this->service->save($request, $user);

        if (! $result['status']) {
            return $this->errorRedirect($result['message']);
        }

        return $this->successRedirect('users.index', $result['message']);
    }

    public function approve(User $user)
    {
        A::require('approve users.user');
        $result = $this->service->approve($user);
        return back()->with('success', $result['message']);
    }

    public function ban(User $user)
    {
        A::require('update users.user');
        $result = $this->service->ban($user);
        return back()->with('success', $result['message']);
    }

    public function unban(User $user)
    {
        A::require('update users.user');
        $result = $this->service->unban($user);
        return back()->with('success', $result['message']);
    }
}

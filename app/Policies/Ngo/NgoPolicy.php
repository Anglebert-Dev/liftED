<?php

namespace App\Policies\Ngo;

use App\Helpers\AuthHelper as A;
use App\Models\Ngo;
use App\Models\User;

class NgoPolicy
{
    public function viewAny(User $user): bool
    {
        return A::can('list users.ngo');
    }

    public function view(User $user, Ngo $ngo): bool
    {
        return A::can('read users.ngo');
    }

    public function create(User $user): bool
    {
        return A::can('create users.ngo');
    }

    public function update(User $user, Ngo $ngo): bool
    {
        return A::can('update users.ngo');
    }

    public function delete(User $user, Ngo $ngo): bool
    {
        return A::can('delete users.ngo');
    }
}

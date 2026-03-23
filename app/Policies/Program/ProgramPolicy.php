<?php

namespace App\Policies\Program;

use App\Helpers\AuthHelper as A;
use App\Models\Program\Program;
use App\Models\User;

class ProgramPolicy
{
    public function viewAny(User $user): bool
    {
        return A::can('list programs.program');
    }

    public function view(User $user, Program $program): bool
    {
        if (! A::can('read programs.program')) {
            return false;
        }
        if ($user->role === 'ngo_staff') {
            return $user->ngo_id === $program->ngo_id;
        }
        return true;
    }

    public function create(User $user): bool
    {
        return A::can('create programs.program');
    }

    public function update(User $user, Program $program): bool
    {
        return A::can('update programs.program') && $user->ngo_id === $program->ngo_id;
    }

    public function delete(User $user, Program $program): bool
    {
        return A::can('delete programs.program') && $user->ngo_id === $program->ngo_id;
    }
}

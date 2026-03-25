<?php

namespace App\Policies\Program;

use App\Helpers\AuthHelper as A;
use App\Models\Enrollment\Enrollment;
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

        if ($user->role === 'superadmin') {
            return A::can('view all programs');
        }

        if ((int) $user->ngo_id !== (int) $program->ngo_id) {
            return false;
        }

        if ($user->role === 'ngo_staff') {
            return true;
        }

        if ($user->role === 'learner') {
            return Enrollment::where('learner_id', $user->id)
                ->where('program_id', $program->id)
                ->exists();
        }

        if ($user->role === 'mentor') {
            return Enrollment::where('program_id', $program->id)
                ->where('mentor_id', $user->id)
                ->exists();
        }

        return false;
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

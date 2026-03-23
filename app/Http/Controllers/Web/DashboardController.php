<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Repositories\Enrollment\EnrollmentRepository;
use App\Repositories\Program\ProgramRepository;
use App\Repositories\User\UserRepository;

class DashboardController extends Controller
{
    public function __construct(
        private ProgramRepository    $programRepo,
        private EnrollmentRepository $enrollmentRepo,
        private UserRepository       $userRepo,
    ) {}

    public function superadmin()
    {
        $stats = [
            'total_users'    => \App\Models\User::count(),
            'total_programs' => \App\Models\Program\Program::count(),
            'pending_users'  => \App\Models\User::where('is_approved', false)->count(),
        ];
        return view('dashboard.superadmin', compact('stats'));
    }

    public function ngoStaff()
    {
        $user     = auth()->user();
        $programs = $this->programRepo->getWithMaterialCount($user->ngo_id);
        return view('dashboard.ngo-staff', compact('programs'));
    }

    public function mentor()
    {
        $enrollments = $this->enrollmentRepo->getByMentor(auth()->id());
        return view('dashboard.mentor', compact('enrollments'));
    }

    public function learner()
    {
        $enrollments = $this->enrollmentRepo->getByLearner(auth()->id());
        return view('dashboard.learner', compact('enrollments'));
    }
}

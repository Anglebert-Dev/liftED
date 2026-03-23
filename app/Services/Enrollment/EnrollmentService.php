<?php

namespace App\Services\Enrollment;

use App\Models\Enrollment\Enrollment;
use App\Repositories\Enrollment\EnrollmentRepository;
use App\Services\BaseService;
use Illuminate\Http\Request;

class EnrollmentService extends BaseService
{
    public function __construct(private EnrollmentRepository $repo) {}

    public function save(Request $request, ?Enrollment $enrollment = null): array
    {
        try {
            $isNew      = is_null($enrollment);
            $enrollment = $enrollment ?? new Enrollment();

            // Prevent duplicate enrollments
            if ($isNew && $this->repo->isEnrolled(
                $request->input('learner_id'),
                $request->input('program_id')
            )) {
                return $this->failure('Learner is already enrolled in this program.');
            }

            $enrollment->fill([
                'learner_id' => $request->input('learner_id'),
                'program_id' => $request->input('program_id'),
                'mentor_id'  => $request->input('mentor_id'),
                'enrolled_at' => $isNew ? now() : $enrollment->enrolled_at,
            ]);

            $this->repo->save($enrollment);

            return $this->success($enrollment, $isNew ? 'Learner enrolled.' : 'Enrollment updated.');
        } catch (\Throwable $e) {
            return $this->failure('Could not save enrollment: ' . $e->getMessage());
        }
    }

    public function delete(Enrollment $enrollment): array
    {
        try {
            $this->repo->delete($enrollment);
            return $this->success(null, 'Enrollment removed.');
        } catch (\Throwable $e) {
            return $this->failure('Could not remove enrollment.');
        }
    }
}

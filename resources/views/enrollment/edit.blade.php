@extends('layouts.app')
@section('title', isset($enrollment) ? 'Edit Enrollment' : 'Enroll Learner')
@section('page-title', isset($enrollment) ? 'Edit Enrollment' : 'Enroll Learner')
@section('breadcrumb', 'Programs / ' . $program->title . ' / Enrollments / ' . (isset($enrollment) ? 'Edit' : 'New'))

@section('content')
<div class="max-w-2xl">
    <x-ui.card :title="isset($enrollment) ? 'Edit Enrollment' : 'Enroll a Learner'">
        <form method="POST"
              action="{{ isset($enrollment)
                ? route('programs.enrollments.update', [$program, $enrollment])
                : route('programs.enrollments.store', $program) }}">
            @csrf
            @if(isset($enrollment)) @method('PUT') @endif

            <input type="hidden" name="program_id" value="{{ $program->id }}" />

            <div class="space-y-4">
                <x-forms.select
                    name="learner_id"
                    label="Learner"
                    :options="$learners->pluck('firstname', 'id')->map(fn($f, $id) => $learners->find($id)->fullName())->toArray()"
                    :selected="old('learner_id', $enrollment->learner_id ?? '')"
                    placeholder="Select a learner…"
                    :required="true" />

                <x-forms.select
                    name="mentor_id"
                    label="Assign Mentor (optional)"
                    :options="$mentors->pluck('firstname', 'id')->map(fn($f, $id) => $mentors->find($id)->fullName())->toArray()"
                    :selected="old('mentor_id', $enrollment->mentor_id ?? '')"
                    placeholder="No mentor assigned" />
            </div>

            <div class="flex items-center gap-3 mt-6 pt-4 border-t border-slate-100">
                <x-ui.button
                    type="submit"
                    label="{{ isset($enrollment) ? 'Update Enrollment' : 'Enroll Learner' }}"
                    variant="primary" />
                <x-ui.button
                    :href="route('programs.enrollments.index', $program)"
                    label="Cancel"
                    variant="secondary" />
            </div>
        </form>
    </x-ui.card>
</div>
@endsection

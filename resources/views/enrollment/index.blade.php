@use('App\Helpers\AuthHelper', 'A')
@extends('layouts.app')
@section('title', 'Enrollments')
@section('page-title', 'Enrollments')
@section('breadcrumb', 'Programs / ' . $program->title . ' / Enrollments')

@section('content')
<div class="flex items-center justify-between mb-5">
    <p class="text-sm text-slate-500">
        <span class="font-medium text-textmain">{{ $program->title }}</span>
        — {{ $enrollments->count() }} learner(s) enrolled
    </p>
    <div class="flex items-center gap-2">
        <x-ui.button :href="route('programs.show', $program)" label="← Program" variant="secondary" size="sm" />
        @if(A::can('create learners.enrollment'))
            <x-ui.button :href="route('programs.enrollments.create', $program)" label="+ Enroll Learner" variant="primary" />
        @endif
    </div>
</div>

@if($enrollments->isEmpty())
    <x-ui.empty-state
        title="No learners enrolled"
        description="Enroll learners and assign mentors to this program."
        actionLabel="Enroll Learner"
        :actionRoute="route('programs.enrollments.create', $program)" />
@else
    <x-ui.card>
        <x-table.table :headers="['Learner', 'Mentor', 'Enrolled On', 'Actions']">
            @foreach($enrollments as $enrollment)
                <x-table.table-row>
                    <td class="px-4 py-3">
                        <p class="font-medium text-textmain">{{ $enrollment->learner->fullName() }}</p>
                        <p class="text-xs text-slate-400">{{ $enrollment->learner->email }}</p>
                    </td>
                    <td class="px-4 py-3 text-sm text-slate-600">
                        {{ $enrollment->mentor?->fullName() ?? '—' }}
                    </td>
                    <td class="px-4 py-3 text-xs text-slate-400">
                        {{ $enrollment->enrolled_at?->format('d M Y') ?? '—' }}
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-2">
                            @if(A::can('update learners.enrollment'))
                                <x-ui.button
                                    :href="route('programs.enrollments.edit', [$program, $enrollment])"
                                    label="Edit"
                                    variant="secondary"
                                    size="sm" />
                            @endif
                            @if(A::can('delete learners.enrollment'))
                                <form method="POST"
                                      action="{{ route('programs.enrollments.destroy', [$program, $enrollment]) }}"
                                      onsubmit="return confirm('Remove this learner from the program?')">
                                    @csrf @method('DELETE')
                                    <x-ui.button type="submit" label="Remove" variant="danger" size="sm" />
                                </form>
                            @endif
                        </div>
                    </td>
                </x-table.table-row>
            @endforeach
        </x-table.table>
    </x-ui.card>
@endif
@endsection

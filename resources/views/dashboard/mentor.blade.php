@extends('layouts.app')
@section('title', 'Mentor Dashboard')
@section('page-title', 'My Learners')

@section('content')
@if($enrollments->isEmpty())
    <x-ui.empty-state title="No learners assigned" description="You have not been assigned to any learners yet." />
@else
    <x-ui.card title="Assigned Learners" :description="$enrollments->count() . ' learner(s) across all programs'">
        <x-table.table :headers="['Learner', 'Program', 'Enrolled', 'Actions']">
            @foreach($enrollments as $enrollment)
                <x-table.table-row>
                    <td class="px-4 py-3">
                        <p class="font-medium text-textmain">{{ $enrollment->learner->fullName() }}</p>
                        <p class="text-xs text-slate-400">{{ $enrollment->learner->email }}</p>
                    </td>
                    <td class="px-4 py-3 text-sm text-slate-600">{{ $enrollment->program->title }}</td>
                    <td class="px-4 py-3 text-xs text-slate-400">
                        {{ $enrollment->enrolled_at?->format('d M Y') ?? '—' }}
                    </td>
                    <td class="px-4 py-3">
                        <x-ui.button
                            :href="route('progress.show', [$enrollment->program, $enrollment->learner])"
                            label="View Progress"
                            variant="secondary"
                            size="sm" />
                    </td>
                </x-table.table-row>
            @endforeach
        </x-table.table>
    </x-ui.card>
@endif
@endsection

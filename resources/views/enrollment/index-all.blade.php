@use('App\Helpers\AuthHelper', 'A')
@extends('layouts.app')
@section('title', 'Enrollments')
@section('page-title', 'Enrollments')
@section('breadcrumb', 'Enrollments')

@section('content')
<div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <p class="min-w-0 text-sm text-slate-500">
        @if(A::can('view all programs'))
            All enrollments across organisations.
        @else
            All enrollments in your organisation.
        @endif
        <span class="font-medium text-textmain">{{ $enrollments->count() }}</span> total
    </p>
    <x-ui.button :href="route('programs.index')" label="Programs" variant="secondary" size="sm" />
</div>

@if($enrollments->isEmpty())
    <x-ui.empty-state
        title="No enrollments"
        description="Enroll learners from a program to see them here." />
@else
    <x-ui.card>
        <x-table.table :headers="['Learner', 'Program', 'Mentor', 'Enrolled', 'Actions']">
            @foreach($enrollments as $enrollment)
                <x-table.table-row>
                    <td class="px-4 py-3">
                        <p class="font-medium text-textmain">{{ $enrollment->learner->fullName() }}</p>
                        <p class="text-xs text-slate-400">{{ $enrollment->learner->email }}</p>
                    </td>
                    <td class="px-4 py-3 text-sm text-slate-600">
                        <a href="{{ route('programs.show', $enrollment->program) }}" class="font-medium text-primary hover:underline">
                            {{ $enrollment->program->title }}
                        </a>
                    </td>
                    <td class="px-4 py-3 text-sm text-slate-600">
                        {{ $enrollment->mentor?->fullName() ?? '—' }}
                    </td>
                    <td class="px-4 py-3 text-xs text-slate-400">
                        {{ $enrollment->enrolled_at?->format('d M Y') ?? '—' }}
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex flex-wrap items-center gap-2">
                            @if(A::can('read learners.progress'))
                                <x-ui.button
                                    :href="route('progress.show', [$enrollment->program, $enrollment->learner])"
                                    label="Progress"
                                    variant="secondary"
                                    size="sm" />
                            @endif
                            @if(A::can('update learners.enrollment'))
                                <x-ui.button
                                    :href="route('programs.enrollments.edit', [$enrollment->program, $enrollment])"
                                    label="Edit"
                                    variant="secondary"
                                    size="sm" />
                            @endif
                        </div>
                    </td>
                </x-table.table-row>
            @endforeach
        </x-table.table>
    </x-ui.card>
@endif
@endsection

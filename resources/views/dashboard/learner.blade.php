@extends('layouts.app')
@section('title', 'My Programs')
@section('page-title', 'My Programs')

@section('content')
@if($enrollments->isEmpty())
    <x-ui.empty-state title="Not enrolled yet" description="You have not been enrolled in any programs. Contact your NGO staff." />
@else
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        @foreach($enrollments as $enrollment)
            <div class="bg-white rounded-xl border border-slate-200 p-5">
                <div class="flex items-start justify-between mb-2">
                    <h3 class="font-semibold text-textmain text-sm">{{ $enrollment->program->title }}</h3>
                    <x-ui.badge color="green" label="Enrolled" />
                </div>
                <p class="text-xs text-slate-500 mb-3">
                    {{ $enrollment->program->description ?: 'No description.' }}
                </p>
                @if($enrollment->mentor)
                    <p class="text-xs text-slate-400 mb-4">
                        Mentor: <span class="font-medium text-textmain">{{ $enrollment->mentor->fullName() }}</span>
                    </p>
                @endif
                <div class="flex flex-wrap gap-2">
                    <x-ui.button
                        :href="route('programs.materials.index', $enrollment->program)"
                        label="Materials"
                        variant="primary"
                        size="sm" />
                    @can('read learners.own_progress')
                        <x-ui.button
                            :href="route('programs.progress.me', $enrollment->program)"
                            label="My progress"
                            variant="secondary"
                            size="sm" />
                    @endcan
                </div>
            </div>
        @endforeach
    </div>
@endif
@endsection

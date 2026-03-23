@extends('layouts.app')
@section('title', 'NGO Staff Dashboard')
@section('page-title', 'My Programs')

@section('content')
<div class="flex items-center justify-between mb-6">
    <p class="text-sm text-slate-500">{{ $programs->count() }} program(s) in your NGO</p>
    <x-ui.button href="{{ route('programs.create') }}" label="+ New Program" variant="primary" />
</div>

@if($programs->isEmpty())
    <x-ui.empty-state
        title="No programs yet"
        description="Create your first program to start enrolling learners."
        actionLabel="Create Program"
        :actionRoute="route('programs.create')" />
@else
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        @foreach($programs as $program)
            <div class="bg-white rounded-xl border border-slate-200 p-5 hover:shadow-md transition-shadow">
                <div class="flex items-start justify-between mb-3">
                    <h3 class="font-semibold text-textmain text-sm leading-snug">{{ $program->title }}</h3>
                    <x-ui.badge :color="$program->is_active ? 'green' : 'gray'"
                                :label="$program->is_active ? 'Active' : 'Inactive'" />
                </div>
                <p class="text-xs text-slate-500 mb-4 line-clamp-2">{{ $program->description ?: 'No description.' }}</p>
                <div class="flex items-center gap-4 text-xs text-slate-500 mb-4">
                    <span>{{ $program->learning_materials_count }} material(s)</span>
                    <span>{{ $program->enrollments_count }} learner(s)</span>
                </div>
                <div class="flex items-center gap-2">
                    <x-ui.button :href="route('programs.show', $program)" label="View" variant="secondary" size="sm" />
                    <x-ui.button :href="route('programs.materials.index', $program)" label="Materials" variant="ghost" size="sm" />
                    <x-ui.button :href="route('programs.enrollments.index', $program)" label="Enroll" variant="ghost" size="sm" />
                </div>
            </div>
        @endforeach
    </div>
@endif
@endsection

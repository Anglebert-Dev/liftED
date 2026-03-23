@extends('layouts.app')
@section('title', $program->title)
@section('page-title', $program->title)
@section('breadcrumb', 'Programs / ' . $program->title)

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

    {{-- Details --}}
    <div class="lg:col-span-2 space-y-5">
        <x-ui.card title="Program Details">
            <x-slot:actions>
                <x-ui.button :href="route('programs.edit', $program)" label="Edit" variant="secondary" size="sm" />
            </x-slot:actions>
            <dl class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <dt class="text-xs font-medium text-slate-500 uppercase">Status</dt>
                    <dd class="mt-1">
                        <x-ui.badge :color="$program->is_active ? 'green' : 'gray'"
                                    :label="$program->is_active ? 'Active' : 'Inactive'" />
                    </dd>
                </div>
                <div>
                    <dt class="text-xs font-medium text-slate-500 uppercase">NGO</dt>
                    <dd class="mt-1 text-textmain">{{ $program->ngo->name ?? '—' }}</dd>
                </div>
                <div class="col-span-2">
                    <dt class="text-xs font-medium text-slate-500 uppercase">Description</dt>
                    <dd class="mt-1 text-textmain">{{ $program->description ?: 'No description provided.' }}</dd>
                </div>
            </dl>
        </x-ui.card>

        {{-- Materials --}}
        <x-ui.card title="Learning Materials" :description="$program->learningMaterials->count() . ' file(s)'">
            <x-slot:actions>
                <x-ui.button :href="route('programs.materials.index', $program)" label="Manage Materials" variant="secondary" size="sm" />
            </x-slot:actions>
            @if($program->learningMaterials->isEmpty())
                <p class="text-sm text-slate-400">No materials uploaded yet.</p>
            @else
                <ul class="divide-y divide-slate-100">
                    @foreach($program->learningMaterials->take(5) as $mat)
                        <li class="py-2 flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <x-ui.badge :color="match($mat->type) { 'pdf' => 'red', 'video' => 'blue', default => 'gray' }"
                                            :label="strtoupper($mat->type)" />
                                <span class="text-sm text-textmain">{{ $mat->title }}</span>
                            </div>
                            <x-ui.button :href="route('programs.materials.serve', [$program, $mat])"
                                         label="Download" variant="ghost" size="sm" />
                        </li>
                    @endforeach
                </ul>
            @endif
        </x-ui.card>
    </div>

    {{-- Enrollments sidebar --}}
    <div class="space-y-5">
        <x-ui.card title="Enrollments" :description="$program->enrollments->count() . ' learner(s)'">
            <x-slot:actions>
                <x-ui.button :href="route('programs.enrollments.index', $program)" label="Manage" variant="ghost" size="sm" />
            </x-slot:actions>
            @if($program->enrollments->isEmpty())
                <p class="text-sm text-slate-400">No learners enrolled.</p>
            @else
                <ul class="space-y-2">
                    @foreach($program->enrollments->take(6) as $enrollment)
                        <li class="text-sm">
                            <p class="font-medium text-textmain">{{ $enrollment->learner->fullName() }}</p>
                            <p class="text-xs text-slate-400">
                                Mentor: {{ $enrollment->mentor?->fullName() ?? 'Unassigned' }}
                            </p>
                        </li>
                    @endforeach
                </ul>
            @endif
        </x-ui.card>
    </div>

</div>
@endsection

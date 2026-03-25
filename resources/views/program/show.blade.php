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
                @can('update', $program)
                    <x-ui.button :href="route('programs.edit', $program)" label="Edit" variant="secondary" size="sm" />
                @endcan
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
                @can('upload programs.material')
                    <x-ui.button :href="route('programs.materials.index', $program)" label="Manage Materials" variant="secondary" size="sm" />
                @else
                    <x-ui.button :href="route('programs.materials.index', $program)" label="View Materials" variant="secondary" size="sm" />
                @endcan
            </x-slot:actions>
            @if($program->learningMaterials->isEmpty())
                <p class="text-sm text-slate-400">No materials yet.</p>
            @else
                <ul class="divide-y divide-slate-100">
                    @foreach($program->learningMaterials->take(5) as $mat)
                        <li class="py-3 flex flex-col gap-1 sm:flex-row sm:items-start sm:justify-between">
                            <div class="min-w-0">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <x-ui.badge :color="match($mat->type) { 'pdf' => 'red', 'video' => 'blue', 'link' => 'amber', default => 'gray' }"
                                                :label="strtoupper($mat->type)" />
                                    <span class="text-sm font-medium text-textmain">{{ $mat->title }}</span>
                                </div>
                                @if(filled($mat->description))
                                    <p class="text-xs text-slate-500 mt-1">{{ Str::limit($mat->description, 120) }}</p>
                                @endif
                            </div>
                            @can('read programs.material')
                                <div class="flex items-center gap-2 flex-shrink-0">
                                    @if($mat->hasExternalUrl())
                                        <x-ui.button :href="route('programs.materials.visit', [$program, $mat])"
                                                     label="Open link" variant="ghost" size="sm"
                                                     target="_blank" rel="noopener noreferrer" />
                                    @endif
                                    @if($mat->hasStoredFile())
                                        <x-ui.button :href="route('programs.materials.serve', [$program, $mat])"
                                                     label="Download" variant="ghost" size="sm" />
                                    @endif
                                </div>
                            @endcan
                        </li>
                    @endforeach
                </ul>
            @endif
        </x-ui.card>
    </div>

    @can('list learners.enrollment')
        {{-- Enrollments sidebar (staff) --}}
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
    @else
        <div class="space-y-5">
            @can('read learners.own_progress')
                <x-ui.card title="Your progress">
                    <p class="text-sm text-slate-600 mb-3">See which materials you have opened and mentor feedback.</p>
                    <x-ui.button :href="route('programs.progress.me', $program)" label="View my progress" variant="primary" class="w-full" />
                </x-ui.card>
            @endcan
        </div>
    @endcan

</div>
@endsection

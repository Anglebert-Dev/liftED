@extends('layouts.app')
@section('title', 'My progress — ' . $program->title)
@section('page-title', 'My progress')
@section('breadcrumb', 'Programs / ' . $program->title . ' / Progress')

@section('content')
<div class="grid grid-cols-1 gap-5 lg:grid-cols-3">

    <div class="lg:col-span-2">
        <x-ui.card
            title="Your activity"
            :description="$program->title . ' — ' . $progressRecords->count() . ' material(s) with activity'">

            <p class="mb-3 text-xs text-slate-500">
                <span class="font-medium text-textmain">Completed</span> means you pressed “Mark complete” for that resource.
                Mentor notes for each material appear in the last column when your mentor adds them.
            </p>

            @if($progressRecords->isEmpty())
                <p class="text-sm text-slate-400">
                    Open materials from <a href="{{ route('programs.materials.index', $program) }}" class="font-medium text-primary underline">Learning Materials</a>
                    to start tracking progress here.
                </p>
            @else
                <x-table.table :headers="['Material', 'Type', 'Viewed', 'Downloaded', 'Status', 'Mentor note']">
                    @foreach($progressRecords as $record)
                        @php
                            $matFeedback = $feedbackByMaterial->get((string) $record->material_id);
                        @endphp
                        <x-table.table-row>
                            <td class="px-4 py-3 text-sm font-medium text-textmain">
                                {{ $record->material->title }}
                            </td>
                            <td class="px-4 py-3">
                                <x-ui.badge
                                    :color="match($record->material->type) { 'pdf' => 'red', 'video' => 'blue', default => 'gray' }"
                                    :label="strtoupper($record->material->type)" />
                            </td>
                            <td class="px-4 py-3 text-xs text-slate-400">
                                {{ $record->viewed_at?->format('d M Y H:i') ?? '—' }}
                            </td>
                            <td class="px-4 py-3 text-xs text-slate-400">
                                {{ $record->downloaded_at?->format('d M Y H:i') ?? '—' }}
                            </td>
                            <td class="px-4 py-3">
                                @if($record->completion_status === 'completed')
                                    <x-ui.badge color="green" label="Completed" />
                                @elseif($record->completion_status === 'in_progress')
                                    <x-ui.badge color="amber" label="In progress" />
                                @else
                                    <x-ui.badge color="gray" label="Not started" />
                                @endif
                            </td>
                            <td class="max-w-xs px-4 py-3 text-xs text-slate-600">
                                {{ $matFeedback ? Str::limit($matFeedback->content, 160) : '—' }}
                            </td>
                        </x-table.table-row>
                    @endforeach
                </x-table.table>
            @endif
        </x-ui.card>
    </div>

    <div>
        <x-ui.card title="Mentor feedback">
            @if($fbProgram = $feedbackByMaterial->get('program'))
                <div class="mb-4 rounded-lg bg-slate-50 p-3 text-sm text-slate-700">
                    <p class="text-xs font-medium text-slate-500">Whole program</p>
                    <p class="mt-1">{{ $fbProgram->content }}</p>
                    <p class="mt-2 text-xs text-slate-400">Updated {{ $fbProgram->updated_at->format('d M Y') }}</p>
                </div>
            @endif

            @if($feedbackByMaterial->except('program')->isNotEmpty())
                <p class="mb-2 text-xs font-medium text-slate-500">Per material</p>
                <ul class="space-y-3 text-sm text-slate-700">
                    @foreach($feedbackByMaterial->except('program') as $fb)
                        @if($fb->material)
                            <li class="rounded-lg border border-slate-100 bg-white p-3">
                                <p class="text-xs font-medium text-textmain">{{ $fb->material->title }}</p>
                                <p class="mt-1">{{ $fb->content }}</p>
                                <p class="mt-2 text-xs text-slate-400">{{ $fb->updated_at->format('d M Y') }}</p>
                            </li>
                        @endif
                    @endforeach
                </ul>
            @elseif(! $feedbackByMaterial->get('program'))
                <p class="text-sm text-slate-400">Your mentor has not left feedback yet.</p>
            @endif
        </x-ui.card>

        <div class="mt-4 space-y-2">
            <x-ui.button :href="route('programs.materials.index', $program)" label="Learning materials"
                         variant="secondary" class="w-full" />
            <x-ui.button :href="route('dashboard.learner')" label="← My programs"
                         variant="ghost" class="w-full" />
        </div>
    </div>

</div>
@endsection

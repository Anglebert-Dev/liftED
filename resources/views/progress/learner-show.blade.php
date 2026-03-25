@extends('layouts.app')
@section('title', 'My progress — ' . $program->title)
@section('page-title', 'My progress')
@section('breadcrumb', 'Programs / ' . $program->title . ' / Progress')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

    <div class="lg:col-span-2">
        <x-ui.card
            title="Your activity"
            :description="$program->title . ' — ' . $progressRecords->count() . ' material(s) with activity'">

            <p class="text-xs text-slate-500 mb-3">
                <span class="font-medium text-textmain">Completed</span> means you pressed “Mark complete” for that resource.
                Downloads and opened links count as activity but do not auto-complete items.
            </p>

            @if($progressRecords->isEmpty())
                <p class="text-sm text-slate-400">
                    Open materials from <a href="{{ route('programs.materials.index', $program) }}" class="text-primary underline font-medium">Learning Materials</a>
                    to start tracking progress here.
                </p>
            @else
                <x-table.table :headers="['Material', 'Type', 'Viewed', 'Downloaded', 'Status']">
                    @foreach($progressRecords as $record)
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
                        </x-table.table-row>
                    @endforeach
                </x-table.table>
            @endif
        </x-ui.card>
    </div>

    <div>
        <x-ui.card title="Mentor feedback">
            @if($feedback)
                <div class="bg-slate-50 rounded-lg p-3 text-sm text-slate-700">
                    {{ $feedback->content }}
                    <p class="text-xs text-slate-400 mt-2">
                        Last updated {{ $feedback->updated_at->format('d M Y') }}
                    </p>
                </div>
            @else
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

@use('App\Helpers\AuthHelper', 'A')
@extends('layouts.app')
@section('title', 'Learning Materials')
@section('page-title', 'Learning Materials')
@section('breadcrumb', 'Programs / ' . $program->title . ' / Materials')

@section('content')
<div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
    <div class="min-w-0">
        <p class="text-sm text-slate-500">Materials for <span class="font-medium text-textmain">{{ $program->title }}</span></p>
    </div>
    <div class="flex flex-wrap items-center gap-2">
        <x-ui.button :href="route('programs.show', $program)" label="← Program" variant="secondary" size="sm" />
        @if(A::can('upload programs.material'))
            <x-ui.button :href="route('programs.materials.create', $program)" label="+ Add material" variant="primary" />
        @endif
    </div>
</div>

@if($materials->isEmpty())
    @if(A::can('upload programs.material'))
        <x-ui.empty-state
            title="No materials yet"
            description="Upload files or add links with a short description for learners."
            actionLabel="Add material"
            :actionRoute="route('programs.materials.create', $program)" />
    @else
        <x-ui.empty-state
            title="No materials yet"
            description="No resources have been added to this program yet." />
    @endif
@else
    <x-ui.card>
        <x-table.table :headers="['Resource', 'Type', 'Added', 'Actions']">
            @foreach($materials as $material)
                @php
                    $progress = $progressByMaterialId[$material->id] ?? null;
                    $isDone = $progress && $progress->completion_status === 'completed';
                @endphp
                <x-table.table-row>
                    <td class="px-4 py-3 max-w-md">
                        <p class="font-medium text-textmain">{{ $material->title }}</p>
                        @if(filled($material->description))
                            <p class="text-xs text-slate-500 mt-1 leading-relaxed">{{ Str::limit($material->description, 200) }}</p>
                        @endif
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap">
                        <x-ui.badge
                            :color="match($material->type) {
                                'pdf' => 'red',
                                'video' => 'blue',
                                'image' => 'purple',
                                'link' => 'amber',
                                default => 'gray',
                            }"
                            :label="strtoupper($material->type)" />
                    </td>
                    <td class="px-4 py-3 text-xs text-slate-400 whitespace-nowrap">{{ $material->created_at->format('d M Y') }}</td>
                    <td class="px-4 py-3">
                        <div class="flex flex-wrap items-center gap-2">
                            @if($material->hasExternalUrl())
                                <x-ui.button
                                    :href="route('programs.materials.visit', [$program, $material])"
                                    label="Open link"
                                    variant="secondary"
                                    size="sm"
                                    target="_blank"
                                    rel="noopener noreferrer" />
                            @endif
                            @if($material->hasStoredFile())
                                <x-ui.button
                                    :href="route('programs.materials.serve', [$program, $material])"
                                    label="Download"
                                    variant="{{ $material->hasExternalUrl() ? 'ghost' : 'secondary' }}"
                                    size="sm" />
                            @endif
                            @if(A::can('read learners.own_progress'))
                                @if($isDone)
                                    <x-ui.badge color="green" label="Completed" />
                                @else
                                    <form method="POST" action="{{ route('programs.materials.complete', [$program, $material]) }}" class="inline">
                                        @csrf
                                        <x-ui.button type="submit" label="Mark complete" variant="primary" size="sm" />
                                    </form>
                                @endif
                            @endif
                            @if(A::can('update programs.material'))
                                <x-ui.button
                                    :href="route('programs.materials.edit', [$program, $material])"
                                    label="Edit"
                                    variant="ghost"
                                    size="sm" />
                            @endif
                            @if(A::can('delete programs.material'))
                                <form method="POST"
                                      action="{{ route('programs.materials.destroy', [$program, $material]) }}"
                                      onsubmit="return confirm('Delete this material?')">
                                    @csrf @method('DELETE')
                                    <x-ui.button type="submit" label="Delete" variant="danger" size="sm" />
                                </form>
                            @endif
                        </div>
                    </td>
                </x-table.table-row>
            @endforeach
        </x-table.table>
    </x-ui.card>

    @if(A::can('read learners.own_progress'))
        <p class="text-xs text-slate-500 mt-3">
            <span class="font-medium text-textmain">Tip:</span> Downloading or opening a link only shows activity.
            Use <span class="font-medium">Mark complete</span> when you have finished studying the resource.
        </p>
    @endif
@endif
@endsection

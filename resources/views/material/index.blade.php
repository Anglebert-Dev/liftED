@use('App\Helpers\AuthHelper', 'A')
@extends('layouts.app')
@section('title', 'Learning Materials')
@section('page-title', 'Learning Materials')
@section('breadcrumb', 'Programs / ' . $program->title . ' / Materials')

@section('content')
<div class="flex items-center justify-between mb-5">
    <div>
        <p class="text-sm text-slate-500">Materials for <span class="font-medium text-textmain">{{ $program->title }}</span></p>
    </div>
    <div class="flex items-center gap-2">
        <x-ui.button :href="route('programs.show', $program)" label="← Program" variant="secondary" size="sm" />
        @if(A::can('upload programs.material'))
            <x-ui.button :href="route('programs.materials.create', $program)" label="+ Upload Material" variant="primary" />
        @endif
    </div>
</div>

@if($materials->isEmpty())
    <x-ui.empty-state
        title="No materials yet"
        description="Upload PDFs, videos, or documents for this program."
        actionLabel="Upload Material"
        :actionRoute="route('programs.materials.create', $program)" />
@else
    <x-ui.card>
        <x-table.table :headers="['Title', 'Type', 'Uploaded', 'Actions']">
            @foreach($materials as $material)
                <x-table.table-row>
                    <td class="px-4 py-3">
                        <p class="font-medium text-textmain">{{ $material->title }}</p>
                    </td>
                    <td class="px-4 py-3">
                        <x-ui.badge
                            :color="match($material->type) { 'pdf' => 'red', 'video' => 'blue', 'image' => 'purple', default => 'gray' }"
                            :label="strtoupper($material->type)" />
                    </td>
                    <td class="px-4 py-3 text-xs text-slate-400">{{ $material->created_at->format('d M Y') }}</td>
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-2">
                            <x-ui.button
                                :href="route('programs.materials.serve', [$program, $material])"
                                label="Download"
                                variant="secondary"
                                size="sm" />
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
@endif
@endsection

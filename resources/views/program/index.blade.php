@extends('layouts.app')
@section('title', 'Programs')
@section('page-title', 'Programs')

@section('content')
<div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <p class="text-sm text-slate-500">{{ $programs->total() }} program(s)</p>
    @can('create', \App\Models\Program\Program::class)
        <x-ui.button href="{{ route('programs.create') }}" label="+ New Program" variant="primary" class="w-full sm:w-auto" />
    @endcan
</div>

@if($programs->isEmpty())
    <x-ui.empty-state title="No programs found"
        description="Create your first education program."
        actionLabel="Create Program"
        :actionRoute="route('programs.create')" />
@else
    <x-ui.card>
        <x-table.table :headers="['Title', 'Status', 'Materials', 'Enrollments', 'Actions']">
            @foreach($programs as $program)
                <x-table.table-row>
                    <td class="px-4 py-3">
                        <p class="font-medium text-textmain">{{ $program->title }}</p>
                        <p class="text-xs text-slate-400 truncate max-w-xs">{{ Str::limit($program->description, 60) }}</p>
                    </td>
                    <td class="px-4 py-3">
                        <x-ui.badge :color="$program->is_active ? 'green' : 'gray'"
                                    :label="$program->is_active ? 'Active' : 'Inactive'" />
                    </td>
                    <td class="px-4 py-3 text-sm text-slate-600">{{ $program->learning_materials_count ?? '—' }}</td>
                    <td class="px-4 py-3 text-sm text-slate-600">{{ $program->enrollments_count ?? '—' }}</td>
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-2">
                            <x-ui.button :href="route('programs.show', $program)" label="View" variant="secondary" size="sm" />
                            @can('update', $program)
                                <x-ui.button :href="route('programs.edit', $program)" label="Edit" variant="ghost" size="sm" />
                            @endcan
                            @can('delete', $program)
                                <form method="POST" action="{{ route('programs.destroy', $program) }}"
                                      onsubmit="return confirm('Delete this program?')">
                                    @csrf @method('DELETE')
                                    <x-ui.button type="submit" label="Delete" variant="danger" size="sm" />
                                </form>
                            @endcan
                        </div>
                    </td>
                </x-table.table-row>
            @endforeach
        </x-table.table>
        <x-table.pagination :paginator="$programs" />
    </x-ui.card>
@endif
@endsection

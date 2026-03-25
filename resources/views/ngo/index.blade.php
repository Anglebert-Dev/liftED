@use('App\Helpers\AuthHelper', 'A')
@extends('layouts.app')
@section('title', 'NGOs')
@section('page-title', 'NGOs')

@section('content')
<div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <p class="text-sm text-slate-500">{{ $ngos->total() }} organisation(s)</p>
    @if(A::can('create users.ngo'))
        <x-ui.button href="{{ route('ngos.create') }}" label="+ New NGO" variant="primary" class="w-full sm:w-auto" />
    @endif
</div>

<x-ui.card>
    <x-table.table :headers="['Name', 'Description', 'Programs', 'Users', 'Actions']">
        @foreach($ngos as $ngo)
            <x-table.table-row>
                <td class="px-4 py-3">
                    <p class="font-medium text-textmain text-sm">{{ $ngo->name }}</p>
                </td>
                <td class="px-4 py-3 text-sm text-slate-600 max-w-md">
                    {{ $ngo->description ? Str::limit($ngo->description, 120) : '—' }}
                </td>
                <td class="px-4 py-3 text-sm text-textmain">{{ $ngo->programs_count }}</td>
                <td class="px-4 py-3 text-sm text-textmain">{{ $ngo->users_count }}</td>
                <td class="px-4 py-3">
                    <div class="flex items-center gap-1 flex-wrap">
                        @if(A::can('update users.ngo'))
                            <x-ui.button :href="route('ngos.edit', $ngo)" label="Edit" variant="secondary" size="sm" />
                        @endif
                        @if(A::can('delete users.ngo'))
                            <form method="POST" action="{{ route('ngos.destroy', $ngo) }}"
                                  onsubmit="return confirm('Delete {{ $ngo->name }}? This is only allowed if there are no programs or users tied to it.')">
                                @csrf @method('DELETE')
                                <x-ui.button type="submit" label="Delete" variant="danger" size="sm" />
                            </form>
                        @endif
                    </div>
                </td>
            </x-table.table-row>
        @endforeach
    </x-table.table>
    <x-table.pagination :paginator="$ngos" />
</x-ui.card>
@endsection

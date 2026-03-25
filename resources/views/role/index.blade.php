@use('App\Helpers\AuthHelper', 'A')
@extends('layouts.app')
@section('title', 'Roles & Permissions')
@section('page-title', 'Roles & Permissions')

@section('content')
<div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <p class="text-sm text-slate-500">{{ $roles->count() }} role(s)</p>
    @if(A::can('create users.role'))
        <x-ui.button href="{{ route('roles.create') }}" label="+ New Role" variant="primary" class="w-full sm:w-auto" />
    @endif
</div>

<x-ui.card>
    <x-table.table :headers="['Role', 'Permissions', 'Assigned Users', 'Actions']">
        @foreach($roles as $role)
            <x-table.table-row>
                <td class="px-4 py-3">
                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 rounded-full bg-primary/10 flex items-center justify-center flex-shrink-0">
                            <span class="text-primary text-xs font-semibold">
                                {{ strtoupper(substr($role->name, 0, 1)) }}
                            </span>
                        </div>
                        <div>
                            <p class="font-medium text-textmain text-sm">{{ $role->name }}</p>
                            @if($role->name === 'SuperAdmin')
                                <p class="text-xs text-slate-400">Full access — all permissions</p>
                            @endif
                        </div>
                    </div>
                </td>
                <td class="px-4 py-3">
                    @if($role->name === 'SuperAdmin')
                        <x-ui.badge color="purple" label="All permissions" />
                    @else
                        <span class="text-sm text-textmain font-medium">{{ $role->permissions->count() }}</span>
                        <span class="text-xs text-slate-400 ml-1">permission(s)</span>
                    @endif
                </td>
                <td class="px-4 py-3">
                    <span class="text-sm text-textmain font-medium">{{ $role->users_count }}</span>
                    <span class="text-xs text-slate-400 ml-1">user(s)</span>
                </td>
                <td class="px-4 py-3">
                    <div class="flex items-center gap-1 flex-wrap">
                        @if(A::can('update users.role'))
                            <x-ui.button :href="route('roles.edit', $role)" label="Edit" variant="secondary" size="sm" />
                        @endif

                        @if($role->name !== 'SuperAdmin' && A::can('delete users.role'))
                            <form method="POST" action="{{ route('roles.destroy', $role) }}"
                                  onsubmit="return confirm('Delete role \'{{ $role->name }}\'? This will revoke it from all assigned users.')">
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
@endsection

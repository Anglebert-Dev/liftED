@use('App\Helpers\AuthHelper', 'A')
@extends('layouts.app')
@section('title', 'Users')
@section('page-title', 'Users')

@section('content')
<div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <p class="text-sm text-slate-500">{{ $users->total() }} user(s)</p>
    <div class="flex flex-wrap items-center gap-2">
        @if(A::can('list users.role'))
            <x-ui.button href="{{ route('roles.index') }}" label="Manage Roles" variant="secondary" />
        @endif
        @if(A::can('create users.user'))
            <x-ui.button href="{{ route('users.create') }}" label="+ New User" variant="primary" />
        @endif
    </div>
</div>

<x-ui.card>
    <x-table.table :headers="['Name', 'Email', 'Role', 'NGO', 'Status', 'Actions']">
        @foreach($users as $user)
            <x-table.table-row>
                <td class="px-4 py-3">
                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 rounded-full bg-primary/10 flex items-center justify-center flex-shrink-0">
                            <span class="text-primary text-xs font-semibold">
                                {{ strtoupper(substr($user->firstname, 0, 1)) }}
                            </span>
                        </div>
                        <p class="font-medium text-textmain text-sm">{{ $user->fullName() }}</p>
                    </div>
                </td>
                <td class="px-4 py-3 text-sm text-slate-600">{{ $user->email }}</td>
                <td class="px-4 py-3">
                    <x-ui.badge
                        :color="match($user->role) { 'superadmin' => 'purple', 'ngo_staff' => 'blue', 'mentor' => 'green', default => 'gray' }"
                        :label="str_replace('_', ' ', ucfirst($user->role))" />
                </td>
                <td class="px-4 py-3 text-sm text-slate-500">{{ $user->ngo?->name ?? '—' }}</td>
                <td class="px-4 py-3">
                    @if($user->isBanned())
                        <x-ui.badge color="red" label="Banned" />
                    @elseif($user->isApproved())
                        <x-ui.badge color="green" label="Active" />
                    @else
                        <x-ui.badge color="amber" label="Pending" />
                    @endif
                </td>
                <td class="px-4 py-3">
                    <div class="flex items-center gap-1 flex-wrap">
                        <x-ui.button :href="route('users.show', $user)" label="View" variant="secondary" size="sm" />

                        @if(A::can('update users.user'))
                            <x-ui.button :href="route('users.edit', $user)" label="Edit" variant="ghost" size="sm" />
                        @endif

                        @if(! $user->isApproved() && A::can('approve users.user'))
                            <form method="POST" action="{{ route('users.approve', $user) }}">
                                @csrf
                                <x-ui.button type="submit" label="Approve" variant="accent" size="sm" />
                            </form>
                        @endif

                        @if($user->isBanned())
                            <form method="POST" action="{{ route('users.unban', $user) }}">
                                @csrf
                                <x-ui.button type="submit" label="Unban" variant="secondary" size="sm" />
                            </form>
                        @elseif($user->id !== auth()->id())
                            <form method="POST" action="{{ route('users.ban', $user) }}"
                                  onsubmit="return confirm('Ban this user?')">
                                @csrf
                                <x-ui.button type="submit" label="Ban" variant="danger" size="sm" />
                            </form>
                        @endif
                    </div>
                </td>
            </x-table.table-row>
        @endforeach
    </x-table.table>
    <x-table.pagination :paginator="$users" />
</x-ui.card>
@endsection

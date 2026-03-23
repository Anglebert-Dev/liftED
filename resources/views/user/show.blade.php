@use('App\Helpers\AuthHelper', 'A')
@extends('layouts.app')
@section('title', $user->fullName())
@section('page-title', $user->fullName())
@section('breadcrumb', 'Users / ' . $user->fullName())

@section('content')
<div class="max-w-2xl">
    <x-ui.card title="User Details">
        <x-slot:actions>
            @if(A::can('update users.user'))
                <x-ui.button :href="route('users.edit', $user)" label="Edit" variant="secondary" size="sm" />
            @endif
        </x-slot:actions>

        <dl class="grid grid-cols-2 gap-5 text-sm">
            <div>
                <dt class="text-xs font-medium text-slate-500 uppercase">First Name</dt>
                <dd class="mt-1 text-textmain">{{ $user->firstname }}</dd>
            </div>
            <div>
                <dt class="text-xs font-medium text-slate-500 uppercase">Last Name</dt>
                <dd class="mt-1 text-textmain">{{ $user->lastname }}</dd>
            </div>
            <div>
                <dt class="text-xs font-medium text-slate-500 uppercase">Email</dt>
                <dd class="mt-1 text-textmain">{{ $user->email }}</dd>
            </div>
            <div>
                <dt class="text-xs font-medium text-slate-500 uppercase">Phone</dt>
                <dd class="mt-1 text-textmain">{{ $user->phone_number ?: '—' }}</dd>
            </div>
            <div>
                <dt class="text-xs font-medium text-slate-500 uppercase">Role</dt>
                <dd class="mt-1">
                    <x-ui.badge
                        :color="match($user->role) { 'superadmin' => 'purple', 'ngo_staff' => 'blue', 'mentor' => 'green', default => 'gray' }"
                        :label="str_replace('_', ' ', ucfirst($user->role))" />
                </dd>
            </div>
            <div>
                <dt class="text-xs font-medium text-slate-500 uppercase">NGO</dt>
                <dd class="mt-1 text-textmain">{{ $user->ngo?->name ?? '—' }}</dd>
            </div>
            <div>
                <dt class="text-xs font-medium text-slate-500 uppercase">Account Status</dt>
                <dd class="mt-1">
                    @if($user->isBanned())
                        <x-ui.badge color="red" label="Banned" />
                    @elseif($user->isApproved())
                        <x-ui.badge color="green" label="Active" />
                    @else
                        <x-ui.badge color="amber" label="Pending Approval" />
                    @endif
                </dd>
            </div>
            <div>
                <dt class="text-xs font-medium text-slate-500 uppercase">UUID</dt>
                <dd class="mt-1 text-slate-400 font-mono text-xs">{{ $user->uuid }}</dd>
            </div>
        </dl>

        <div class="mt-6 pt-4 border-t border-slate-100 flex items-center gap-3">
            <x-ui.button :href="route('users.index')" label="← Back to Users" variant="secondary" />
        </div>
    </x-ui.card>
</div>
@endsection

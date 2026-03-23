@extends('layouts.app')
@section('title', 'SuperAdmin Dashboard')
@section('page-title', 'SuperAdmin Dashboard')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">
    <div class="bg-white rounded-xl border border-slate-200 p-5">
        <p class="text-xs font-medium text-slate-500 uppercase tracking-wide">Total Users</p>
        <p class="text-3xl font-bold text-textmain mt-1">{{ $stats['total_users'] }}</p>
    </div>
    <div class="bg-white rounded-xl border border-slate-200 p-5">
        <p class="text-xs font-medium text-slate-500 uppercase tracking-wide">Total Programs</p>
        <p class="text-3xl font-bold text-textmain mt-1">{{ $stats['total_programs'] }}</p>
    </div>
    <div class="bg-white rounded-xl border border-slate-200 p-5 {{ $stats['pending_users'] > 0 ? 'border-amber-300 bg-amber-50' : '' }}">
        <p class="text-xs font-medium text-slate-500 uppercase tracking-wide">Pending Approvals</p>
        <p class="text-3xl font-bold {{ $stats['pending_users'] > 0 ? 'text-accent' : 'text-textmain' }} mt-1">
            {{ $stats['pending_users'] }}
        </p>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-5">
    <x-ui.card title="Quick Actions">
        <div class="flex flex-col gap-3">
            <x-ui.button href="{{ route('users.create') }}" label="Create New User" variant="primary" />
            <x-ui.button href="{{ route('users.index') }}" label="Manage Users" variant="secondary" />
            <x-ui.button href="{{ route('programs.index') }}" label="View All Programs" variant="secondary" />
        </div>
    </x-ui.card>

    <x-ui.card title="Pending Approvals">
        @php $pending = \App\Models\User::where('is_approved', false)->latest()->take(5)->get(); @endphp
        @if($pending->isEmpty())
            <p class="text-sm text-slate-500">No pending approvals.</p>
        @else
            <ul class="divide-y divide-slate-100">
                @foreach($pending as $u)
                    <li class="py-2 flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-textmain">{{ $u->fullName() }}</p>
                            <p class="text-xs text-slate-400">{{ $u->email }} · {{ $u->role }}</p>
                        </div>
                        <form method="POST" action="{{ route('users.approve', $u) }}">
                            @csrf
                            <x-ui.button type="submit" label="Approve" variant="accent" size="sm" />
                        </form>
                    </li>
                @endforeach
            </ul>
        @endif
    </x-ui.card>
</div>
@endsection

@extends('layouts.auth')
@section('title', 'Sign in')

@section('content')
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-8">
    <h2 class="text-xl font-bold text-textmain mb-1">Welcome back</h2>
    <p class="text-sm text-slate-500 mb-6">Sign in to your LiftED account</p>

    @if($errors->any())
        <x-ui.alert type="error" :message="$errors->first()" class="mb-5" />
    @endif

    <form method="POST" action="{{ route('login.post') }}" class="space-y-4">
        @csrf
        <x-forms.input name="email" label="Email address" type="email"
            placeholder="you@example.com" :required="true" />
        <x-forms.input name="password" label="Password" type="password"
            placeholder="••••••••" :required="true" />

        <div class="flex items-center justify-between">
            <label class="flex items-center gap-2 text-sm text-slate-600 cursor-pointer">
                <input type="checkbox" name="remember" class="rounded border-slate-300 text-primary focus:ring-primary">
                Remember me
            </label>
        </div>

        <x-ui.button type="submit" label="Sign in" variant="primary" class="w-full" />
    </form>
</div>
<p class="text-center text-xs text-slate-400 mt-4">LiftED — African Leadership University &copy; {{ date('Y') }}</p>
@endsection

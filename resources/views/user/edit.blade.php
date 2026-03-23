@extends('layouts.app')
@section('title', isset($user) ? 'Edit User' : 'New User')
@section('page-title', isset($user) ? 'Edit User' : 'New User')
@section('breadcrumb', 'Users / ' . (isset($user) ? $user->fullName() : 'New'))

@section('content')
<div class="max-w-2xl">
    <x-ui.card :title="isset($user) ? 'Edit User' : 'Create User'">
        <form method="POST"
              action="{{ isset($user) ? route('users.update', $user) : route('users.store') }}">
            @csrf
            @if(isset($user)) @method('PUT') @endif

            <div class="grid grid-cols-2 gap-4 mb-4">
                <x-forms.input
                    name="firstname"
                    label="First Name"
                    :value="old('firstname', $user->firstname ?? '')"
                    :required="true" />
                <x-forms.input
                    name="lastname"
                    label="Last Name"
                    :value="old('lastname', $user->lastname ?? '')"
                    :required="true" />
            </div>

            <div class="space-y-4">
                <x-forms.input
                    name="email"
                    label="Email Address"
                    type="email"
                    :value="old('email', $user->email ?? '')"
                    :required="true" />

                <x-forms.input
                    name="phone_number"
                    label="Phone Number"
                    :value="old('phone_number', $user->phone_number ?? '')"
                    placeholder="+250 7XX XXX XXX" />

                <x-forms.select
                    name="role"
                    label="Role"
                    :options="['superadmin' => 'SuperAdmin', 'ngo_staff' => 'NGO Staff', 'mentor' => 'Mentor', 'learner' => 'Learner']"
                    :selected="old('role', $user->role ?? '')"
                    :required="true" />

                @php $ngos = \App\Models\Ngo::pluck('name', 'id')->toArray(); @endphp
                <x-forms.select
                    name="ngo_id"
                    label="NGO (optional)"
                    :options="$ngos"
                    :selected="old('ngo_id', $user->ngo_id ?? '')"
                    placeholder="No NGO assigned" />

                <div class="flex items-center gap-3">
                    <input type="hidden" name="is_approved" value="0" />
                    <input type="checkbox" name="is_approved" id="is_approved" value="1"
                           class="rounded border-slate-300 text-primary focus:ring-primary"
                           {{ old('is_approved', $user->is_approved ?? false) ? 'checked' : '' }}>
                    <label for="is_approved" class="text-sm text-textmain">Approved (can log in)</label>
                </div>

                <div class="border-t border-slate-100 pt-4">
                    <p class="text-xs font-medium text-slate-500 uppercase mb-3">
                        {{ isset($user) ? 'Change Password (leave blank to keep current)' : 'Password' }}
                    </p>
                    <div class="grid grid-cols-2 gap-4">
                        <x-forms.input
                            name="password"
                            label="Password"
                            type="password"
                            :required="!isset($user)" />
                        <x-forms.input
                            name="password_confirmation"
                            label="Confirm Password"
                            type="password"
                            :required="!isset($user)" />
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-3 mt-6 pt-4 border-t border-slate-100">
                <x-ui.button
                    type="submit"
                    label="{{ isset($user) ? 'Update User' : 'Create User' }}"
                    variant="primary" />
                <x-ui.button href="{{ route('users.index') }}" label="Cancel" variant="secondary" />
            </div>
        </form>
    </x-ui.card>
</div>
@endsection

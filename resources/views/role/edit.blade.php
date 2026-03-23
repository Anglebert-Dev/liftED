@extends('layouts.app')
@section('title', isset($role) ? 'Edit Role' : 'New Role')
@section('page-title', isset($role) ? 'Edit Role' : 'New Role')
@section('breadcrumb', 'Roles / ' . (isset($role) ? $role->name : 'New'))

@section('content')
<div class="max-w-3xl">
    <form method="POST"
          action="{{ isset($role) ? route('roles.update', $role) : route('roles.store') }}">
        @csrf
        @if(isset($role)) @method('PUT') @endif

        {{-- Role name --}}
        <x-ui.card :title="isset($role) ? 'Edit Role' : 'Create Role'" class="mb-5">
            <x-forms.input
                name="name"
                label="Role Name"
                :value="old('name', $role->name ?? '')"
                placeholder="e.g. Content Manager"
                :required="true" />
        </x-ui.card>

        {{-- Permissions --}}
        <x-ui.card title="Permissions" description="Select which actions this role can perform.">
            <div class="space-y-4">
                @foreach($permissions as $module => $controllers)
                    <div class="border border-slate-200 rounded-lg overflow-hidden">

                        {{-- Module header --}}
                        <div class="bg-slate-50 px-4 py-3 flex items-center justify-between">
                            <h4 class="text-xs font-bold text-slate-700 uppercase tracking-wider">
                                {{ ucfirst($module) }}
                            </h4>
                            <label class="flex items-center gap-1.5 text-xs text-slate-500 cursor-pointer select-none">
                                <input type="checkbox"
                                       class="module-toggle rounded border-slate-300 text-primary focus:ring-primary"
                                       data-module="{{ $module }}">
                                Select all
                            </label>
                        </div>

                        {{-- Controller groups --}}
                        @foreach($controllers as $controller => $perms)
                            <div class="px-4 py-3 border-t border-slate-100">
                                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-2">
                                    {{ ucfirst($controller) }}
                                </p>
                                <div class="flex flex-wrap gap-x-5 gap-y-2">
                                    @foreach($perms as $perm)
                                        @php
                                            $action = explode(' ', $perm->name)[0];
                                            $isChecked = isset($role) && $role->hasPermissionTo($perm->name);
                                        @endphp
                                        <label class="flex items-center gap-1.5 text-sm cursor-pointer select-none">
                                            <input type="checkbox"
                                                   name="permissions[]"
                                                   value="{{ $perm->name }}"
                                                   class="perm-cb perm-module-{{ $module }} rounded border-slate-300 text-primary focus:ring-primary"
                                                   {{ $isChecked ? 'checked' : '' }}>
                                            <span class="text-textmain">{{ $action }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach

                    </div>
                @endforeach
            </div>

            <div class="flex items-center gap-3 mt-6 pt-4 border-t border-slate-100">
                <x-ui.button
                    type="submit"
                    label="{{ isset($role) ? 'Update Role' : 'Create Role' }}"
                    variant="primary" />
                <x-ui.button href="{{ route('roles.index') }}" label="Cancel" variant="secondary" />
            </div>
        </x-ui.card>
    </form>
</div>

<script>
    document.querySelectorAll('.module-toggle').forEach(function (toggle) {
        const module = toggle.dataset.module;
        const checkboxes = document.querySelectorAll('.perm-module-' + module);

        // Sync toggle state on load
        toggle.checked = [...checkboxes].every(cb => cb.checked);
        toggle.indeterminate = !toggle.checked && [...checkboxes].some(cb => cb.checked);

        toggle.addEventListener('change', function () {
            checkboxes.forEach(cb => cb.checked = this.checked);
        });

        checkboxes.forEach(function (cb) {
            cb.addEventListener('change', function () {
                const allChecked = [...checkboxes].every(cb => cb.checked);
                const someChecked = [...checkboxes].some(cb => cb.checked);
                toggle.checked = allChecked;
                toggle.indeterminate = !allChecked && someChecked;
            });
        });
    });
</script>
@endsection

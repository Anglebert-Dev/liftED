@extends('layouts.app')
@section('title', isset($ngo) ? 'Edit NGO' : 'New NGO')
@section('page-title', isset($ngo) ? 'Edit NGO' : 'New NGO')
@section('breadcrumb', 'NGOs / ' . (isset($ngo) ? $ngo->name : 'New'))

@section('content')
<div class="max-w-2xl">
    <x-ui.card :title="isset($ngo) ? 'Edit organisation' : 'Create organisation'">
        <form method="POST"
              action="{{ isset($ngo) ? route('ngos.update', $ngo) : route('ngos.store') }}">
            @csrf
            @if(isset($ngo)) @method('PUT') @endif

            <div class="space-y-5">
                <x-forms.input
                    name="name"
                    label="Organisation name"
                    :value="old('name', $ngo->name ?? '')"
                    placeholder="e.g. African Leadership University"
                    :required="true" />

                <x-forms.textarea
                    name="description"
                    label="Description"
                    :value="old('description', $ngo->description ?? '')"
                    placeholder="Optional short description of the NGO."
                    :rows="4"
                    :required="false" />
            </div>

            <div class="flex items-center gap-3 mt-6 pt-4 border-t border-slate-100">
                <x-ui.button
                    type="submit"
                    label="{{ isset($ngo) ? 'Save changes' : 'Create NGO' }}"
                    variant="primary" />
                <x-ui.button :href="route('ngos.index')" label="Cancel" variant="secondary" />
            </div>
        </form>
    </x-ui.card>
</div>
@endsection

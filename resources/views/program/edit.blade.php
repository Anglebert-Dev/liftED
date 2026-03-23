@extends('layouts.app')
@section('title', isset($program) ? 'Edit Program' : 'New Program')
@section('page-title', isset($program) ? 'Edit Program' : 'New Program')
@section('breadcrumb', 'Programs / ' . (isset($program) ? $program->title : 'New'))

@section('content')
<div class="max-w-2xl">
    <x-ui.card :title="isset($program) ? 'Edit Program' : 'Create Program'">
        <form method="POST"
              action="{{ isset($program) ? route('programs.update', $program) : route('programs.store') }}">
            @csrf
            @if(isset($program)) @method('PUT') @endif

            <div class="space-y-4">
                <x-forms.input
                    name="title"
                    label="Program Title"
                    :value="old('title', $program->title ?? '')"
                    placeholder="e.g. Digital Literacy 2025"
                    :required="true" />

                <x-forms.textarea
                    name="description"
                    label="Description"
                    :value="old('description', $program->description ?? '')"
                    placeholder="What will learners achieve in this program?" />

                <div class="flex items-center gap-3">
                    <input type="hidden" name="is_active" value="0" />
                    <input type="checkbox" name="is_active" id="is_active" value="1"
                           class="rounded border-slate-300 text-primary focus:ring-primary"
                           {{ old('is_active', $program->is_active ?? true) ? 'checked' : '' }}>
                    <label for="is_active" class="text-sm text-textmain">Active (visible to learners)</label>
                </div>
            </div>

            <div class="flex items-center gap-3 mt-6 pt-4 border-t border-slate-100">
                <x-ui.button type="submit" label="{{ isset($program) ? 'Update Program' : 'Create Program' }}" variant="primary" />
                <x-ui.button href="{{ route('programs.index') }}" label="Cancel" variant="secondary" />
            </div>
        </form>
    </x-ui.card>
</div>
@endsection

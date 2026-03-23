@extends('layouts.app')
@section('title', isset($material) ? 'Edit Material' : 'Upload Material')
@section('page-title', isset($material) ? 'Edit Material' : 'Upload Material')
@section('breadcrumb', 'Programs / ' . $program->title . ' / Materials / ' . (isset($material) ? $material->title : 'New'))

@section('content')
<div class="max-w-2xl">
    <x-ui.card :title="isset($material) ? 'Edit Material' : 'Upload Materials'">
        <form method="POST"
              action="{{ isset($material)
                ? route('programs.materials.update', [$program, $material])
                : route('programs.materials.store', $program) }}"
              enctype="multipart/form-data">
            @csrf
            @if(isset($material)) @method('PUT') @endif

            <input type="hidden" name="program_id" value="{{ $program->id }}" />

            <div class="space-y-5">

                @if(isset($material))
                    {{-- Edit mode: single file + editable title --}}
                    <x-forms.input
                        name="title"
                        label="Material Title"
                        :value="old('title', $material->title)"
                        placeholder="e.g. Introduction to Entrepreneurship - Week 1"
                        :required="true" />

                    <x-forms.file-upload
                        name="file"
                        label="Replace File (optional)"
                        accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.mp4,.mov,.avi,.mkv,.png,.jpg,.jpeg"
                        hint="PDF, Word, PowerPoint, Excel, Video, Image — max 100MB" />

                    <p class="text-xs text-slate-400">
                        Current file type: <span class="font-medium text-textmain">{{ strtoupper($material->type) }}</span>
                        — leave blank to keep existing file.
                    </p>

                @else
                    {{-- Create mode: multiple files, titles auto-derived from filenames --}}
                    <div class="p-3 bg-blue-50 border border-blue-200 rounded-lg text-xs text-blue-700">
                        Select one or more files to upload. Each file's title will be set automatically from its filename.
                    </div>

                    <x-forms.file-upload
                        name="files[]"
                        label="Select Files"
                        accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.mp4,.mov,.avi,.mkv,.png,.jpg,.jpeg"
                        hint="PDF, Word, PowerPoint, Excel, Video, Image — max 100MB each"
                        :required="true"
                        :multiple="true" />

                    @error('files')
                        <p class="text-xs text-red-600">{{ $message }}</p>
                    @enderror
                    @error('files.*')
                        <p class="text-xs text-red-600">{{ $message }}</p>
                    @enderror
                @endif

            </div>

            <div class="flex items-center gap-3 mt-6 pt-4 border-t border-slate-100">
                <x-ui.button
                    type="submit"
                    label="{{ isset($material) ? 'Update Material' : 'Upload Files' }}"
                    variant="primary" />
                <x-ui.button
                    :href="route('programs.materials.index', $program)"
                    label="Cancel"
                    variant="secondary" />
            </div>
        </form>
    </x-ui.card>
</div>
@endsection

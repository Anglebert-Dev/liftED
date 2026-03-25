@extends('layouts.app')
@section('title', isset($material) ? 'Edit Material' : 'Add Material')
@section('page-title', isset($material) ? 'Edit Material' : 'Add Material')
@section('breadcrumb', 'Programs / ' . $program->title . ' / Materials / ' . (isset($material) ? $material->title : 'New'))

@section('content')
<div class="max-w-2xl space-y-8">
    @isset($material)
        <x-ui.card title="Edit Material">
            <form method="POST"
                  action="{{ route('programs.materials.update', [$program, $material]) }}"
                  enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <input type="hidden" name="program_id" value="{{ $program->id }}" />

                <div class="space-y-5">
                    <x-forms.input
                        name="title"
                        label="Title"
                        :value="old('title', $material->title)"
                        placeholder="e.g. Week 1 — Introduction video"
                        :required="true" />

                    <x-forms.textarea
                        name="description"
                        label="Short description"
                        :value="old('description', $material->description)"
                        placeholder="What this resource is about (optional)."
                        :rows="3"
                        :required="false" />

                    <x-forms.input
                        name="external_url"
                        type="url"
                        label="External link"
                        :value="old('external_url', $material->external_url)"
                        placeholder="https://…"
                        :required="false" />

                    <x-forms.file-upload
                        name="file"
                        label="Replace file (optional)"
                        accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.mp4,.mov,.avi,.mkv,.png,.jpg,.jpeg"
                        hint="Leave blank to keep the current file. PDF, Office, video, or image — max 100MB." />

                    <p class="text-xs text-slate-500">
                        @if($material->hasStoredFile())
                            Current file type: <span class="font-medium text-textmain">{{ strtoupper($material->type) }}</span>
                        @else
                            No file stored — this item is link-based unless you upload a file.
                        @endif
                    </p>
                </div>

                <div class="flex items-center gap-3 mt-6 pt-4 border-t border-slate-100">
                    <x-ui.button type="submit" label="Save changes" variant="primary" />
                    <x-ui.button :href="route('programs.materials.index', $program)" label="Cancel" variant="secondary" />
                </div>
            </form>
        </x-ui.card>
    @else
        <x-ui.card title="Upload files">
            <p class="text-sm text-slate-600 mb-4">Each file becomes one material. Titles are taken from the filenames.</p>
            <form method="POST" action="{{ route('programs.materials.store', $program) }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="creation_mode" value="upload" />
                <input type="hidden" name="program_id" value="{{ $program->id }}" />

                <x-forms.file-upload
                    name="files[]"
                    label="Files"
                    accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.mp4,.mov,.avi,.mkv,.png,.jpg,.jpeg"
                    hint="PDF, Word, PowerPoint, Excel, video, or image — max 100MB each"
                    :required="true"
                    :multiple="true" />

                @error('files')
                    <p class="text-xs text-red-600">{{ $message }}</p>
                @enderror
                @error('files.*')
                    <p class="text-xs text-red-600">{{ $message }}</p>
                @enderror

                <div class="mt-6">
                    <x-ui.button type="submit" label="Upload" variant="primary" />
                </div>
            </form>
        </x-ui.card>

        <x-ui.card title="Add a link or resource">
            <p class="text-sm text-slate-600 mb-4">
                Add a website, YouTube or Vimeo video, or any https link. You can optionally attach a file as well.
            </p>
            <form method="POST" action="{{ route('programs.materials.store', $program) }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="creation_mode" value="link" />
                <input type="hidden" name="program_id" value="{{ $program->id }}" />

                <div class="space-y-5">
                    <x-forms.input
                        name="title"
                        label="Title"
                        :value="old('title')"
                        placeholder="e.g. Khan Academy — Algebra basics"
                        :required="true" />

                    <x-forms.textarea
                        name="description"
                        label="Short description"
                        :value="old('description')"
                        placeholder="What learners should focus on or how long it might take."
                        :rows="3"
                        :required="false" />

                    <x-forms.input
                        name="external_url"
                        type="url"
                        label="Link URL"
                        :value="old('external_url')"
                        placeholder="https://…"
                        :required="true" />

                    <x-forms.file-upload
                        name="file"
                        label="Optional attachment"
                        accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.mp4,.mov,.avi,.mkv,.png,.jpg,.jpeg"
                        hint="Optional file learners can download in addition to the link." />
                </div>

                @error('title')
                    <p class="text-xs text-red-600 mt-2">{{ $message }}</p>
                @enderror
                @error('external_url')
                    <p class="text-xs text-red-600 mt-2">{{ $message }}</p>
                @enderror

                <div class="mt-6">
                    <x-ui.button type="submit" label="Add resource" variant="primary" />
                </div>
            </form>
        </x-ui.card>

        <x-ui.button :href="route('programs.materials.index', $program)" label="← Back to materials" variant="secondary" />
    @endisset
</div>
@endsection

@extends('layouts.app')
@section('title', 'Progress: ' . $learner->fullName())
@section('page-title', $learner->fullName() . ' — Progress')
@section('breadcrumb', 'Progress / ' . $program->title . ' / ' . $learner->fullName())

@php
    $oldMaterial = old('material_id');
    $feedbackKey = ($oldMaterial === '' || $oldMaterial === null) ? 'program' : (string) $oldMaterial;
    $prefillContent = old('content', $feedbackByMaterial->get($feedbackKey)?->content ?? '');
@endphp

@section('content')
<div class="grid grid-cols-1 gap-5 lg:grid-cols-3">

    {{-- Progress records --}}
    <div class="lg:col-span-2">
        <x-ui.card
            title="Activity Log"
            :description="$program->title . ' — ' . $progressRecords->count() . ' material(s) tracked'">

            <p class="mb-3 text-xs text-slate-500">
                <span class="font-medium text-textmain">Completed</span> is set when the learner marks a resource complete.
                Mentor notes can be added per material (or one general note for the whole program) in the panel on the right.
            </p>

            @if($progressRecords->isEmpty())
                <p class="text-sm text-slate-400">This learner hasn't accessed any materials yet.</p>
            @else
                <x-table.table :headers="['Material', 'Type', 'Viewed', 'Downloaded', 'Status', 'Mentor note']">
                    @foreach($progressRecords as $record)
                        @php
                            $matFeedback = $feedbackByMaterial->get((string) $record->material_id);
                        @endphp
                        <x-table.table-row>
                            <td class="px-4 py-3 text-sm font-medium text-textmain">
                                {{ $record->material->title }}
                            </td>
                            <td class="px-4 py-3">
                                <x-ui.badge
                                    :color="match($record->material->type) { 'pdf' => 'red', 'video' => 'blue', default => 'gray' }"
                                    :label="strtoupper($record->material->type)" />
                            </td>
                            <td class="px-4 py-3 text-xs text-slate-400">
                                {{ $record->viewed_at?->format('d M Y H:i') ?? '—' }}
                            </td>
                            <td class="px-4 py-3 text-xs text-slate-400">
                                {{ $record->downloaded_at?->format('d M Y H:i') ?? '—' }}
                            </td>
                            <td class="px-4 py-3">
                                @if($record->completion_status === 'completed')
                                    <x-ui.badge color="green" label="Completed" />
                                @elseif($record->completion_status === 'in_progress')
                                    <x-ui.badge color="amber" label="In Progress" />
                                @else
                                    <x-ui.badge color="gray" label="Not Started" />
                                @endif
                            </td>
                            <td class="max-w-xs px-4 py-3 text-xs text-slate-600">
                                {{ $matFeedback ? Str::limit($matFeedback->content, 120) : '—' }}
                            </td>
                        </x-table.table-row>
                    @endforeach
                </x-table.table>
            @endif
        </x-ui.card>
    </div>

    {{-- Feedback panel --}}
    <div>
        <x-ui.card title="Mentor feedback">
            @if($fbProgram = $feedbackByMaterial->get('program'))
                <div class="mb-4 rounded-lg bg-slate-50 p-3 text-sm text-slate-700">
                    <p class="text-xs font-medium text-slate-500">Whole program</p>
                    <p class="mt-1">{{ Str::limit($fbProgram->content, 200) }}</p>
                    <p class="mt-2 text-xs text-slate-400">Updated {{ $fbProgram->updated_at->format('d M Y') }}</p>
                </div>
            @endif

            <form method="POST" action="{{ route('progress.feedback.store') }}" class="space-y-4">
                @csrf
                <input type="hidden" name="learner_id" value="{{ $learner->id }}" />
                <input type="hidden" name="program_id" value="{{ $program->id }}" />

                <x-forms.select
                    name="material_id"
                    label="About"
                    :options="$feedbackMaterialOptions"
                    :selected="old('material_id', '')"
                    placeholder="Whole program (general)"
                    :required="false" />

                <x-forms.textarea
                    name="content"
                    label="Your feedback"
                    :value="$prefillContent"
                    placeholder="Write feedback for the selected scope. Saving updates the note for that scope only."
                    :rows="5"
                    :required="true" />

                <x-ui.button type="submit"
                             label="Save feedback"
                             variant="primary"
                             class="w-full" />
            </form>
        </x-ui.card>

        <div class="mt-4">
            <x-ui.button :href="route('progress.index')" label="← Back to Learners"
                         variant="secondary" class="w-full" />
        </div>
    </div>

</div>
@endsection

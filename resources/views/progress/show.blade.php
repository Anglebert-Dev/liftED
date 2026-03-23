@extends('layouts.app')
@section('title', 'Progress: ' . $learner->fullName())
@section('page-title', $learner->fullName() . ' — Progress')
@section('breadcrumb', 'Progress / ' . $program->title . ' / ' . $learner->fullName())

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

    {{-- Progress records --}}
    <div class="lg:col-span-2">
        <x-ui.card
            title="Activity Log"
            :description="$program->title . ' — ' . $progressRecords->count() . ' material(s) tracked'">

            @if($progressRecords->isEmpty())
                <p class="text-sm text-slate-400">This learner hasn't accessed any materials yet.</p>
            @else
                <x-table.table :headers="['Material', 'Type', 'Viewed', 'Downloaded', 'Status']">
                    @foreach($progressRecords as $record)
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
                        </x-table.table-row>
                    @endforeach
                </x-table.table>
            @endif
        </x-ui.card>
    </div>

    {{-- Feedback panel --}}
    <div>
        <x-ui.card title="Mentor Feedback">
            @if($feedback)
                <div class="bg-slate-50 rounded-lg p-3 mb-4 text-sm text-slate-700">
                    {{ $feedback->content }}
                    <p class="text-xs text-slate-400 mt-2">
                        Last updated {{ $feedback->updated_at->format('d M Y') }}
                    </p>
                </div>
            @endif

            <form method="POST" action="{{ route('progress.feedback.store') }}" class="space-y-4">
                @csrf
                <input type="hidden" name="learner_id" value="{{ $learner->id }}" />
                <input type="hidden" name="program_id" value="{{ $program->id }}" />

                <x-forms.textarea
                    name="content"
                    label="{{ $feedback ? 'Update Feedback' : 'Add Feedback' }}"
                    :value="old('content', $feedback->content ?? '')"
                    placeholder="Write your feedback for this learner…"
                    :rows="5"
                    :required="true" />

                <x-ui.button type="submit"
                             label="{{ $feedback ? 'Update Feedback' : 'Save Feedback' }}"
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

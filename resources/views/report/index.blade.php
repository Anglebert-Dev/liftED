@extends('layouts.app')

@section('title', 'Reports')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-textmain">Reports</h1>
            <p class="text-sm text-slate-600">Quick snapshot of onboarding, completions, and support activity.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <x-ui.card title="Learners onboarded">
            <div class="text-3xl font-semibold text-textmain">{{ $totals['learners_onboarded'] }}</div>
        </x-ui.card>
        <x-ui.card title="Enrollments">
            <div class="text-3xl font-semibold text-textmain">{{ $totals['enrollments'] }}</div>
        </x-ui.card>
        <x-ui.card title="Learners completed (any)">
            <div class="text-3xl font-semibold text-textmain">{{ $totals['learners_completed_any'] }}</div>
        </x-ui.card>
        <x-ui.card title="Learners completed (all materials)">
            <div class="text-3xl font-semibold text-textmain">{{ $totals['learners_completed_all'] }}</div>
        </x-ui.card>
        <x-ui.card title="Materials total">
            <div class="text-3xl font-semibold text-textmain">{{ $totals['materials_total'] }}</div>
        </x-ui.card>
        <x-ui.card title="Materials completed">
            <div class="text-3xl font-semibold text-textmain">{{ $totals['materials_completed'] }}</div>
        </x-ui.card>
        <x-ui.card title="Mentors helped">
            <div class="text-3xl font-semibold text-textmain">{{ $totals['mentors_helped'] }}</div>
        </x-ui.card>
        <x-ui.card title="Staff / Mentors / Learners">
            <div class="text-2xl font-semibold text-textmain">
                {{ $totals['staff_total'] }} / {{ $totals['mentors_total'] }} / {{ $totals['learners_total'] }}
            </div>
        </x-ui.card>
    </div>

    <div class="mt-8">
        <div class="flex items-center justify-between mb-3">
            <h2 class="text-lg font-semibold text-textmain">By program</h2>
        </div>

        @if($programSummaries->isEmpty())
            <x-ui.empty-state title="No programs yet" message="Create a program and enroll learners to see reports here." />
        @else
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-slate-50 text-slate-700">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold">Program</th>
                            <th class="px-4 py-3 text-left font-semibold">Learners onboarded</th>
                            <th class="px-4 py-3 text-left font-semibold">Materials</th>
                            <th class="px-4 py-3 text-left font-semibold">Completed records</th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                        @foreach($programSummaries as $row)
                            <tr class="hover:bg-slate-50/60">
                                <td class="px-4 py-3">
                                    <div class="font-medium text-slate-900">{{ $row['program']->title }}</div>
                                </td>
                                <td class="px-4 py-3 text-slate-700">{{ $row['learners_onboarded'] }}</td>
                                <td class="px-4 py-3 text-slate-700">{{ $row['materials_total'] }}</td>
                                <td class="px-4 py-3 text-slate-700">{{ $row['materials_completed'] }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
@endsection


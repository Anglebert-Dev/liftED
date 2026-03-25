@props(['title' => null, 'description' => null])

<div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm {{ $attributes->get('class') }}">
    @if($title)
        <div class="flex flex-col gap-3 border-b border-slate-100 px-4 py-3 sm:flex-row sm:items-start sm:justify-between sm:px-6 sm:py-4">
            <div class="min-w-0">
                <h3 class="text-base font-semibold text-textmain">{{ $title }}</h3>
                @if($description)
                    <p class="mt-0.5 text-sm text-slate-500">{{ $description }}</p>
                @endif
            </div>
            @isset($actions)
                <div class="flex flex-shrink-0 flex-wrap items-center gap-2">
                    {{ $actions }}
                </div>
            @endisset
        </div>
    @endif
    <div class="p-4 sm:p-6">
        {{ $slot }}
    </div>
</div>

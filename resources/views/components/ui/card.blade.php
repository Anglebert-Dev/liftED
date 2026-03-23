@props(['title' => null, 'description' => null])

<div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden {{ $attributes->get('class') }}">
    @if($title)
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="text-base font-semibold text-textmain">{{ $title }}</h3>
                @if($description)
                    <p class="text-sm text-slate-500 mt-0.5">{{ $description }}</p>
                @endif
            </div>
            @isset($actions)
                <div class="flex items-center gap-2">
                    {{ $actions }}
                </div>
            @endisset
        </div>
    @endif
    <div class="p-6">
        {{ $slot }}
    </div>
</div>

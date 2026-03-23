@props(['title' => 'Nothing here yet', 'description' => '', 'actionLabel' => null, 'actionRoute' => null])

<div class="text-center py-16 px-4">
    <div class="w-12 h-12 bg-slate-100 rounded-xl flex items-center justify-center mx-auto mb-4">
        <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
        </svg>
    </div>
    <h3 class="text-sm font-semibold text-textmain mb-1">{{ $title }}</h3>
    @if($description)
        <p class="text-sm text-slate-500 mb-4">{{ $description }}</p>
    @endif
    @if($actionLabel && $actionRoute)
        <x-ui.button variant="primary" size="sm" :href="$actionRoute" :label="$actionLabel" />
    @endif
</div>

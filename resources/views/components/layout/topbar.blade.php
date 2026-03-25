<header class="flex flex-shrink-0 items-center gap-3 border-b border-slate-200 bg-white px-3 py-2.5 sm:px-6 sm:py-3">
    <button type="button"
            id="sidebar-open"
            class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-lg border border-slate-200 text-textmain hover:bg-slate-50 lg:hidden"
            aria-label="Open menu">
        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
    </button>
    <div class="min-w-0 flex-1">
        <h2 class="truncate text-sm font-semibold text-textmain sm:text-base">@yield('page-title', 'Dashboard')</h2>
        @hasSection('breadcrumb')
            <p class="mt-0.5 truncate text-xs text-slate-400">@yield('breadcrumb')</p>
        @endif
    </div>
    <div class="hidden flex-shrink-0 sm:block">
        <span class="whitespace-nowrap text-xs text-slate-400">
            {{ now()->format('D, d M Y') }}
        </span>
    </div>
</header>

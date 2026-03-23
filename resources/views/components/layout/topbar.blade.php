<header class="bg-white border-b border-slate-200 px-6 py-3 flex items-center justify-between flex-shrink-0">
    <div>
        <h2 class="text-sm font-semibold text-textmain">@yield('page-title', 'Dashboard')</h2>
        @hasSection('breadcrumb')
            <p class="text-xs text-slate-400 mt-0.5">@yield('breadcrumb')</p>
        @endif
    </div>
    <div class="flex items-center gap-3">
        <span class="text-xs text-slate-400">
            {{ now()->format('D, d M Y') }}
        </span>
    </div>
</header>

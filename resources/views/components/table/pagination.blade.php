@props(['paginator'])

@if($paginator->hasPages())
    <div class="flex items-center justify-between px-4 py-3 border-t border-slate-200">
        <p class="text-xs text-slate-500">
            Showing {{ $paginator->firstItem() }}–{{ $paginator->lastItem() }} of {{ $paginator->total() }}
        </p>
        <div class="flex items-center gap-1">
            @if($paginator->onFirstPage())
                <span class="px-3 py-1.5 text-xs text-slate-300 border border-slate-200 rounded cursor-not-allowed">Prev</span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}"
                   class="px-3 py-1.5 text-xs text-textmain border border-slate-200 rounded hover:bg-slate-50 transition-colors">Prev</a>
            @endif

            @foreach($paginator->getUrlRange(max(1, $paginator->currentPage()-2), min($paginator->lastPage(), $paginator->currentPage()+2)) as $page => $url)
                @if($page == $paginator->currentPage())
                    <span class="px-3 py-1.5 text-xs bg-primary text-white border border-primary rounded">{{ $page }}</span>
                @else
                    <a href="{{ $url }}" class="px-3 py-1.5 text-xs text-textmain border border-slate-200 rounded hover:bg-slate-50 transition-colors">{{ $page }}</a>
                @endif
            @endforeach

            @if($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}"
                   class="px-3 py-1.5 text-xs text-textmain border border-slate-200 rounded hover:bg-slate-50 transition-colors">Next</a>
            @else
                <span class="px-3 py-1.5 text-xs text-slate-300 border border-slate-200 rounded cursor-not-allowed">Next</span>
            @endif
        </div>
    </div>
@endif

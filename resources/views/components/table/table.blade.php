@props(['headers' => []])

<div class="-mx-4 overflow-x-auto touch-pan-x sm:mx-0 sm:rounded-lg sm:border sm:border-slate-100">
    <table class="min-w-[640px] w-full divide-y divide-slate-200 text-sm">
        <thead class="bg-slate-50">
            <tr>
                @foreach($headers as $header)
                    <th class="whitespace-nowrap px-3 py-2.5 text-left text-xs font-semibold uppercase tracking-wider text-slate-500 sm:px-4 sm:py-3">
                        {{ $header }}
                    </th>
                @endforeach
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 bg-white">
            {{ $slot }}
        </tbody>
    </table>
</div>

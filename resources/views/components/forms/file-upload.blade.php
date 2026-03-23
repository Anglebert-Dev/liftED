@props(['name', 'label' => null, 'accept' => '', 'required' => false, 'hint' => '', 'multiple' => false])

@php $inputId = 'fu-' . str_replace(['[', ']'], '-', $name) . '-' . substr(md5($name . rand()), 0, 6); @endphp

<div class="flex flex-col gap-1">
    @if($label)
        <label for="{{ $inputId }}" class="text-sm font-medium text-textmain">
            {{ $label }}
            @if($required) <span class="text-red-500">*</span> @endif
        </label>
    @endif

    <label for="{{ $inputId }}"
           class="flex flex-col items-center justify-center w-full h-28 border-2 border-dashed rounded-lg cursor-pointer
                  {{ $errors->has($name) ? 'border-red-400 bg-red-50' : 'border-slate-300 bg-slate-50 hover:bg-slate-100' }} transition">
        <div class="flex flex-col items-center gap-1 text-slate-400 text-sm pointer-events-none">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
            </svg>
            <span>Click to upload{{ $multiple ? ' — multiple files allowed' : '' }}</span>
            @if($hint)<span class="text-xs">{{ $hint }}</span>@endif
        </div>
        <input type="file"
               id="{{ $inputId }}"
               name="{{ $name }}"
               accept="{{ $accept }}"
               {{ $multiple ? 'multiple' : '' }}
               class="hidden"
               onchange="renderFileList(this, '{{ $inputId }}-list')" />
    </label>

    <ul id="{{ $inputId }}-list" class="mt-1 space-y-1 hidden"></ul>

    @error($name)
        <p class="text-xs text-red-600 mt-0.5">{{ $message }}</p>
    @enderror
</div>

<script>
    function renderFileList(input, listId) {
        var list = document.getElementById(listId);
        list.innerHTML = '';
        if (!input.files.length) { list.classList.add('hidden'); return; }
        list.classList.remove('hidden');
        Array.from(input.files).forEach(function (file) {
            var size = file.size < 1048576
                ? (file.size / 1024).toFixed(1) + ' KB'
                : (file.size / 1048576).toFixed(1) + ' MB';
            var li = document.createElement('li');
            li.className = 'flex items-center gap-2 text-xs text-slate-600 bg-slate-50 border border-slate-200 rounded px-3 py-1.5';
            li.innerHTML = '<svg class="w-3.5 h-3.5 text-slate-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>'
                + '<span class="truncate flex-1">' + file.name + '</span>'
                + '<span class="text-slate-400 flex-shrink-0">' + size + '</span>';
            list.appendChild(li);
        });
    }
</script>

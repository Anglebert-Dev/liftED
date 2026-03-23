@props(['name', 'label' => null, 'value' => '', 'placeholder' => '', 'rows' => 4, 'required' => false])

<div class="flex flex-col gap-1">
    @if($label)
        <label for="{{ $name }}" class="text-sm font-medium text-textmain">
            {{ $label }}
            @if($required) <span class="text-red-500">*</span> @endif
        </label>
    @endif

    <textarea
        name="{{ $name }}"
        id="{{ $name }}"
        rows="{{ $rows }}"
        placeholder="{{ $placeholder }}"
        {{ $required ? 'required' : '' }}
        {{ $attributes->merge(['class' => 'w-full px-3 py-2 text-sm border rounded-lg bg-white text-textmain placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition resize-none ' . ($errors->has($name) ? 'border-red-400' : 'border-slate-200')]) }}
    >{{ old($name, $value) }}</textarea>

    @error($name)
        <p class="text-xs text-red-600 mt-0.5">{{ $message }}</p>
    @enderror
</div>

@props(['name', 'label' => null, 'options' => [], 'selected' => '', 'placeholder' => 'Select…', 'required' => false])

<div class="flex flex-col gap-1">
    @if($label)
        <label for="{{ $name }}" class="text-sm font-medium text-textmain">
            {{ $label }}
            @if($required) <span class="text-red-500">*</span> @endif
        </label>
    @endif

    <select
        name="{{ $name }}"
        id="{{ $name }}"
        {{ $required ? 'required' : '' }}
        {{ $attributes->merge(['class' => 'w-full px-3 py-2 text-sm border rounded-lg bg-white text-textmain focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition ' . ($errors->has($name) ? 'border-red-400' : 'border-slate-200')]) }}
    >
        <option value="">{{ $placeholder }}</option>
        @foreach($options as $key => $label)
            <option value="{{ $key }}" {{ old($name, $selected) == $key ? 'selected' : '' }}>
                {{ $label }}
            </option>
        @endforeach
    </select>

    @error($name)
        <p class="text-xs text-red-600 mt-0.5">{{ $message }}</p>
    @enderror
</div>

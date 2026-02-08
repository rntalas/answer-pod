@props(['label', 'name', 'oldValue' => 0, 'placeholder' => ''])

<div x-data="{ option: {{ $oldValue }} }" class="flex flex-col gap-4">
    <div class="flex gap-4">
        <label>{{ $label }}</label>

        <div class="flex gap-2 items-center">
            @foreach ([['icon' => 'heroicon-o-pencil-square', 'value' => 0], ['icon' => 'heroicon-o-photo', 'value' => 1]] as $button)
                <button type="button" @click="option = {{ $button['value'] }}"
                    :class="option === {{ $button['value'] }} ?
                        'bg-gray-100 h-6 w-6 border border-zinc-200 p-1 rounded flex justify-center items-center' :
                        'bg-white h-6 w-6 border border-zinc-200 p-1 rounded flex justify-center items-center hover:bg-gray-100'">
                    @svg($button['icon'], 'h-4 w-4')
                </button>
            @endforeach
        </div>
    </div>

    <input type="hidden" name="{{ $name }}" :value="option">

    <template x-if="option === 0">
        <textarea name="{{ $name }}_text" placeholder="{{ $placeholder }}" class="w-full" cols="30" rows="3">{{ old($name . '_text') }}</textarea>
    </template>

    <template x-if="option === 1" x-cloak>
        <x-dropzone-file field="{{ $name }}_image[]" name="{{ $name }}_image" />
    </template>

    @foreach (['_text', '_image'] as $suffix)
        @error($name . $suffix)
            <p class="text-red-500 text-xs">{{ $message }}</p>
        @enderror
    @endforeach
</div>

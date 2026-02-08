<x-layout :title="__('subject.edit')">
    <div class="flex items-center gap-2 mb-4">
        <h1 class="font-bold text-xl">@lang('subject.edit')</h1>
        <span class="text-sm text-gray-600 px-3 py-1 bg-blue-50 rounded-lg">
            {{ $locale->name }}
        </span>
    </div>

    <form action="{{ route('subject.update', $subject) }}" method="POST" class="flex flex-col gap-4">
        @csrf
        @method('PUT')

        <div class="flex flex-col gap-1">
            <label for="name">@lang('subject.label.name')</label>
            <input type="text" id="name" name="name" placeholder="@lang('subject.placeholder.name')"
                value="{{ old('name', $translation->name ?? '') }}" class="rounded-xl w-full px-3 py-2" required>

            @error('name')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex flex-col gap-1">
            <label for="description">@lang('subject.label.description')</label>
            <textarea name="description" id="description" cols="30" rows="8" placeholder="@lang('subject.placeholder.description')"
                class="rounded-xl px-3 py-2">{{ old('description', $translation->description ?? '') }}</textarea>
        </div>

        <div class="flex flex-col gap-2" x-data="{ units: {{ old('units', $subject->units ?? 1) }} }">
            <label for="units">@lang('subject.label.units')</label>
            <input type="number" id="units" name="units" min="1" max="25"
                class="rounded-xl w-fit px-3 py-2" x-model="units" @blur="units = parseInt(units) || 1">

            @error('units')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <input type="hidden" name="locale_id" value="{{ $selectedLocale }}">

        <input type="submit" value="@lang('subject.button.save')" class="btn mt-4">
    </form>
</x-layout>

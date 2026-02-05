<x-layout :title="$subject->name">
    <h1 class="font-bold text-xl mb-4">{{ $subject->name }}</h1>
    <form action="{{ route('subject.update', $subject) }}" method="POST" class="flex flex-col gap-4">
        @csrf
        @method('PUT')
        <div class="flex flex-col gap-4 mb-4">
            <div class="flex flex-col gap-1">
                <label for="name" class="text-lg font-bold">@lang('subject.label.name')</label>
                <input type="text" id="name" name="name" placeholder="@lang('subject.placeholder.name')"
                    value="{{ $subject->name }}" class="rounded-xl w-full px-3 py-2">

                @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="flex flex-col gap-2">
                <label for="units" class="text-lg font-bold">@lang('subject.label.units')</label>
                <input type="number" id="units" name="units" min="1" max="100"
                    value="{{ $subject->units }}" class="rounded-xl w-fit">
            </div>
            <div class="flex flex-col gap-2">
                <label for="locale_id" class="text-lg font-bold">@lang('subject.label.locale')</label>
                <div class="flex gap-2 items-center">
                    <select id="locale_id" name="locale_id" class="rounded px-2 py-1 border">
                        @foreach ($locales as $locale)
                            <option value="{{ $locale->id }}"
                                {{ $locale->id === $subject->locale_id ? 'selected' : '' }}>
                                {{ $locale->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <input type="submit" value="@lang('subject.button.save')" class="btn mt-4">
        </div>
    </form>
</x-layout>

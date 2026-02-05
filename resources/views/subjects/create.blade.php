<x-layout :title="__('subject.create')">
    <h1 class="font-bold text-xl mb-4">@lang('subject.create')</h1>
    <form action="{{ route('subject.store') }}" method="POST" class="flex flex-col gap-4">
        @csrf
        <div class="flex flex-col gap-4 mb-4">
            <div class="flex flex-col gap-1">
                <label for="name">@lang('subject.label.name')</label>
                <input type="text" id="name" name="name" placeholder="@lang('subject.placeholder.name')"
                    value="{{ old('name') }}" class="rounded-xl w-full px-3 py-2">

                @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <label for="description">@lang('subject.label.description')</label>
            <textarea name="description" id="description" cols="30" rows="8" placeholder="@lang('subject.placeholder.description')"
                class="rounded-xl"></textarea>

            <div class="flex flex-col gap-2">
                <label for="units">@lang('subject.label.units')</label>
                <input type="number" id="units" name="units" min="1" max="100"
                    value="{{ old('units', 1) }}" class="rounded-xl w-fit">
            </div>

            <input type="hidden" name="locale_id" value="1">

            <input type="submit" value="@lang('subject.button.add')" class="btn mt-4">
        </div>
    </form>
</x-layout>

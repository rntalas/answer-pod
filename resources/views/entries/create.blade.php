<x-layout :title="__('entry.create')">
    <h1 class="font-bold text-xl mb-4">@lang('entry.create')</h1>
    <form action="{{ route('entry.store', ['unit' => $unit->id]) }}" method="POST" enctype="multipart/form-data"
        class="flex flex-col gap-4">
        @csrf
        <div class="flex flex-col gap-4 mb-4">
            <div class="flex flex-col gap-4">
                @error('unique')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror

                <div class="flex flex-col gap-2" x-data="{ number: {{ old('number', 1) }} }">
                    <label for="number">@lang('entry.label.number')</label>
                    <input type="number" id="number" name="number" min="1" max="100" class="w-fit"
                        required x-model="number" @blur="number = parseInt(number) || 1">
                </div>

                <x-toggle-field :label="__('entry.label.statement')" name="statement" :old-value="old('statement', 0)" :placeholder="__('entry.placeholder.statement')" />

                <x-toggle-field :label="__('entry.label.solution')" name="solution" :old-value="old('solution', 0)" :placeholder="__('entry.placeholder.solution')" />

                <input type="hidden" name="locale_id" value="1">
            </div>
            <input type="submit" value="@lang('entry.button.add')" class="btn mt-4">
        </div>
    </form>
</x-layout>

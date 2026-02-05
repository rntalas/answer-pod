<x-layout :title="$subject->name">
    <h1 class="font-bold text-xl mb-4">{{ $subject->name }}</h1>
    <form action="{{ route('subject.update', $subject) }}" method="POST" class="flex flex-col gap-4"
        x-data="localeForm(@js($subject->translations), {{ $subject->locale_id }})">
        @csrf
        @method('PUT')

        <div class="flex flex-col gap-2">
            <label for="locale_id">@lang('subject.label.locale')</label>
            <select id="locale_id" x-model="selectedLocale" @change="setLocale($event.target.value)"
                class="rounded-xl px-2 py-1 border">
                @foreach ($locales as $locale)
                    <option value="{{ $locale->id }}">{{ $locale->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="flex flex-col gap-1 mt-2">
            <label for="name">@lang('subject.label.name')</label>
            <input type="text" id="name" name="name" placeholder="@lang('subject.placeholder.name')" x-model="fields.name"
                class="rounded-xl w-full px-3 py-2">
        </div>

        <label for="description">@lang('subject.label.description')</label>
        <textarea name="description" id="description" cols="30" rows="8" placeholder="@lang('subject.placeholder.description')"
            class="rounded-xl" x-model="fields.description"></textarea>

        <div class="flex flex-col gap-2">
            <label for="units">@lang('subject.label.units')</label>
            <input type="number" id="units" name="units" min="1" max="100"
                value="{{ $subject->units }}" class="rounded-xl w-fit">
        </div>

        <input type="hidden" name="locale_id" :value="selectedLocale">

        <input type="submit" value="@lang('subject.button.save')" class="btn mt-4">
    </form>
</x-layout>

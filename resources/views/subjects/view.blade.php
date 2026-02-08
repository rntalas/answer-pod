<x-layout :title="$subject->name">
    <div class="flex items-center gap-2 pb-4">
        <h1 class="text-xl font-bold">{{ $subject->name }}</h1>

        <div x-data="{ showLocales: false }" class="relative">
            <button @click="showLocales = !showLocales" class="flex items-center gap-1" title="@lang('app.edit')">
                @svg('heroicon-o-pencil', 'h-5 w-5 cursor-pointer')
                @svg('heroicon-s-chevron-down', 'h-3 w-3')
            </button>

            <div x-show="showLocales" @click.away="showLocales = false" x-cloak
                class="absolute top-full left-0 mt-1 bg-white border rounded-lg shadow-lg p-2 z-10 min-w-[150px]">
                <p class="text-xs text-gray-500 mb-2 px-2">@lang('app.language-selection')</p>
                @foreach ($locales as $locale)
                    <a href="{{ route('subject.edit', ['subject' => $subject->id, 'locale' => $locale->id]) }}"
                        class="flex items-center px-3 py-2 hover:bg-gray-100 rounded text-sm gap-1">
                        {{ $locale->name }}
                        @if ($subject->translations->where('locale_id', $locale->id)->isNotEmpty())
                            <span class="text-green-500">@svg('heroicon-o-check', 'h-4 h-4')</span>
                        @else
                            <span class="text-red-500 text-xs">@svg('heroicon-o-x-mark', 'h-4 h-4')</span>
                        @endif
                    </a>
                @endforeach
            </div>
        </div>

        <x-confirm-delete :action="route('subject.destroy', $subject)" :title="__('subject.confirm')">
            @svg('heroicon-o-trash', 'h-5 w-5 cursor-pointer')
        </x-confirm-delete>
    </div>

    @if (!blank($subject->description))
        <p class="text-lg pb-4">
            {{ $subject->description }}
        </p>
    @endif

    <p class="pb-2">@lang('subject.units')</p>
    <ul class="grid grid-flow-col grid-rows-12 gap-1 list-inside list-disc">
        @foreach ($units as $unit)
            <li>
                <a href="{{ route('unit.show', ['id' => $unit->id]) }}" class="link">
                    @lang('subject.unit', ['number' => $unit->number])
                </a>
            </li>
        @endforeach
    </ul>
</x-layout>

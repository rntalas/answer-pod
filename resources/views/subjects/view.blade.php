<x-layout :title="$subject->name">
    <div class="flex items-center gap-2 pb-4">
        <h1 class="text-xl font-bold">{{ $subject->name }}</h1>

        <a href="{{ route('subject.edit', $subject) }}" title="@lang('app.edit')">
            @svg('heroicon-s-pencil-square', 'h-5 w-5 cursor-pointer')
        </a>

        <x-confirm-delete :action="route('subject.destroy', $subject)" :title="__('subject.confirm')">
            @svg('heroicon-o-trash', 'h-5 w-5 cursor-pointer')
        </x-confirm-delete>
    </div>

    <p class="pb-4">
        @lang('subject.units', ['count' => $subject->units])
    </p>

    <p class="pb-4">
        @lang('subject.entries')
    </p>
</x-layout>

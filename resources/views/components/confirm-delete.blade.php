@props(['action', 'title' => $title ?? ''])

<div x-data="{ open: false }" class="h-4 w-4">
    <button type="button" @click="open = true" {{ $attributes }}>
        {{ $slot }}
    </button>

    <div x-show="open" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
        <div class="bg-white rounded-lg p-6 w-full max-w-sm relative">
            <h2 class="text-lg font-semibold mb-3">
                {{ $title }}
            </h2>

            <button type="button" class="absolute right-4 top-4" @click="open = false">
                @svg('heroicon-s-x-mark', 'h-5 w-5')
            </button>

            <p class="mb-5">
                @lang('app.action-undone')
            </p>

            <div class="flex justify-start gap-3">
                <button type="button" @click="open = false"
                    class="px-4 py-2 border border-zinc-500 rounded-xl hover:shadow-lg transition-all duration-100">
                    @lang('app.cancel')
                </button>

                <form method="POST" action="{{ $action }}">
                    @csrf
                    @method('DELETE')

                    <button type="submit"
                        class="px-4 py-2 bg-red-500 text-white rounded-xl hover:shadow-lg hover:bg-red-700 transiton-all duration-100">
                        @lang('app.delete')
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

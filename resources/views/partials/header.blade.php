<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }}</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('images/favicon.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <header
        class="sticky top-0 flex justify-between items-center px-8 py-4 bg-zinc-100 border-b border-zinc-200 shadow-xl z-10 mb-8">
        <div class="flex justify-center items-center gap-5 lg:gap-12">
            <div class="h-8 w-8 cursor-pointer">
                @svg('heroicon-o-bars-3')
            </div>

            <a href="/">
                <img src="{{ asset('images/logo.png') }}" alt="@lang('Answer Pod')" class="h-5 md:h-10 lg:h-15 w-auto">
            </a>

            @if (Route::is('home'))
                <a href="{{ route('subject.create') }}" class="btn rounded-full">
                    <span class="flex justify-center items-center gap-2 py-2 px-3">
                        @svg('heroicon-o-plus', 'h-5 w-5') @lang('app.resources.subject')
                    </span>
                </a>
            @endif

            @if (Route::is('unit.show'))
                <a href="{{ route('entry.create', ['unit' => request()->route('id')]) }}" class="btn rounded-full">
                    <span class="flex justify-center items-center gap-2 py-2 px-3">
                        @svg('heroicon-o-plus', 'h-5 w-5') @lang('app.resources.entry')
                    </span>
                </a>
            @endif
        </div>

        <div class="flex justify-center items-center gap-4 lg:mr-20">
            <input type="text" placeholder="@lang('app.placeholder.search')"
                class="hidden md:block md:w-48 xl:w-96 rounded-full! bg-white!" name="lesson">

            <div class="w-10 h-10 rounded-full p-2 border border-zinc-200 bg-white cursor-pointer hover:shadow-md">
                @svg('heroicon-o-magnifying-glass')
            </div>

            <div x-data="localeSwitcher(@js($locales))" class="relative inline-block">
                <div @click="toggle()"
                    class="w-10 h-10 rounded-full p-2 border border-zinc-200 bg-white cursor-pointer hover:shadow-md">
                    @svg('heroicon-o-globe-alt')
                </div>

                <ul x-show="open" @click.away="$data.open = false" x-cloak
                    class="absolute right-0 mt-1 border border-zinc-200 bg-white z-10 w-40 rounded-xl overflow-hidden">
                    <template x-for="locale in locales" :key="locale.id">
                        <li @click="setLocale(locale); $data.open = false"
                            class="flex items-center gap-2 px-4 py-2 hover:bg-gray-100 cursor-pointer">
                            <img :src="locale.image" :alt="locale.name" class="h-5 w-5">
                            <span x-text="locale.name"></span>
                        </li>
                    </template>
                </ul>
            </div>
        </div>
    </header>

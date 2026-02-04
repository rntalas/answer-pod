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
                <img src="{{ asset('images/logo.png') }}" alt="logo" class="h-5 md:h-10 lg:h-15 w-auto">
            </a>
        </div>

        <div class="flex justify-center items-center gap-4 lg:mr-20">
            <input type="text" placeholder="Search..." class="hidden md:block md:w-48 xl:w-96 rounded-full bg-white!"
                name="lesson">

            <div class="w-10 h-10 rounded-full p-2 border border-zinc-200 bg-white cursor-pointer hover:shadow-md">
                @svg('heroicon-o-magnifying-glass')
            </div>

            <div x-data="localeSwitcher()" class="relative inline-block">
                <!-- Globe button -->
                <div @click="toggle()"
                    class="w-10 h-10 rounded-full p-2 border border-zinc-200 bg-white cursor-pointer hover:shadow-md">
                    @svg('heroicon-o-globe-alt')
                </div>

                <div x-show="open" @click.away="$data.open = false" x-transition
                    class="absolute mt-2 w-24 bg-white border border-zinc-200 rounded shadow-lg z-50">
                    <template x-for="locale in locales" :key="locale.code">
                        <div @click="setLocale(locale.code)"
                            class="px-2 py-2 cursor-pointer hover:bg-zinc-100 flex justify-start" x-text="locale.label">
                        </div>
                    </template>
                </div>
            </div>

        </div>
    </header>

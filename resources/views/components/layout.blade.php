@include('partials.header')
<main>
    <div class="flex flex-col mx-auto border border-zinc-400 min-h-180 w-135 rounded-xl bg-white p-8 shadow-xl">
        {{ $slot }}
    </div>
</main>
@include('partials.footer')

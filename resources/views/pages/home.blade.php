<x-layout :title="$title">
    <div
        class="flex justify-center mx-auto border border-zinc-400 h-180 w-135 rounded-xl bg-white p-8 shadow-xl relative">
        <div class="flex flex-col flex-1">
            <p class="mb-4">
                <span class="text-xl mb-4">Welcome to <a href="/" class="font-bold hover:underline">Answer
                        Pod</a>!</span><br>
                <span class="text-lg">The best place to store textbook materials for your school subjects.</span>
            </p>
            @if ($subjects->count() > 0)
            @else
                <p class="text-lg">
                    No subjects were found. To create a subject click <a href="/add-subject"
                        class="underline hover:no-underline inline-block">here</a>.
                </p>
            @endif
        </div>

        <div class="absolute bottom-0 mb-6">
            <img src="{{ asset('images/logo.png') }}" alt="logo" class="h-5 lg:h-10 w-auto">
        </div>
    </div>
</x-layout>

<x-layout title="Create a subject">
    <div x-data="{
        title: '',
        submitted: false
    }"
        class="flex justify-center mx-auto border border-zinc-400 h-180 w-135 rounded-xl bg-white p-8 shadow-xl relative px-14 py-8">
        <form class="flex flex-col gap-4 flex-1"
            @submit.prevent="
                submitted = true
                if (!title.trim()) return
                $el.submit()
            ">
            <label class="text-lg">
                Enter the title of your subject.
            </label>

            <input type="text" name="title" placeholder="Subject title" class="rounded-xl border" x-model="title">

            <p x-show="submitted && !title.trim()" x-transition class="text-red-500 text-xs">
                The title is required.
            </p>

            <input type="submit" value="Add Subject" class="btn mt-2">
        </form>

        <div class="absolute bottom-0 mb-6">
            <img src="{{ asset('images/logo.png') }}" alt="logo" class="h-5 lg:h-10 w-auto">
        </div>
    </div>
</x-layout>

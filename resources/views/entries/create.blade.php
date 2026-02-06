@vite(['resources/js/editor.js'])

<x-layout :title="__('entry.create')">
    <h1 class="font-bold text-xl mb-4">@lang('entry.create')</h1>
    <form action="{{ route('entry.store') }}" method="POST" class="flex flex-col gap-4">
        @csrf
        <div class="flex flex-col gap-4 mb-4">
            <div x-data="{ subjects: @js($subjects), subject: null }" class="flex flex-col gap-4">
                <div class="flex flex-col gap-2">
                    <label for="subject_id">@lang('entry.label.subject')</label>
                    <select id="subject_id" class="px-2 py-1 border"
                        @change="subject = subjects.find(s => s.id == $event.target.value)">
                        <option value="">@lang('entry.placeholder.subject')</option>
                        @foreach ($subjects as $subject)
                            <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex flex-col gap-2" x-show="subject?.units" x-cloak>
                    <label for="unit_id">@lang('entry.label.unit')</label>
                    <select id="unit_id" class="px-2 py-1 border">
                        <template x-for="i in subject?.units">
                            <option :value="i" x-text="i"></option>
                        </template>
                    </select>
                </div>

                <div class="flex flex-col gap-2" x-show="subject?.units" x-cloak>
                    <label for="number">@lang('entry.label.number')</label>
                    <input type="number" id="number" name="number" min="1" max="100"
                        value="{{ old('number', 1) }}" class="w-fit">
                </div>

                <div x-data="{ option: 1 }" class="flex flex-col gap-4" x-show="subject?.units" x-cloak>
                    <div class="flex gap-4">
                        <label>@lang('entry.label.statement')</label>

                        <div class="flex gap-2 items-center">
                            <button type="button" @click="option = 1"
                                :class="option === 1 ?
                                    'bg-gray-100 h-6 w-6 border border-zinc-200 p-1 rounded flex justify-center items-center' :
                                    'bg-white h-6 w-6 border border-zinc-200 p-1 rounded flex justify-center items-center hover:bg-gray-100'">
                                @svg('heroicon-o-pencil-square', 'h-4 w-4')
                            </button>

                            <button type="button" @click="option = 2"
                                :class="option === 2 ?
                                    'bg-gray-100 h-6 w-6 border border-zinc-200 p-1 rounded flex justify-center items-center' :
                                    'bg-white h-6 w-6 border border-zinc-200 p-1 rounded flex justify-center items-center hover:bg-gray-100'">
                                @svg('heroicon-o-photo', 'h-4 w-4')

                            </button>
                        </div>
                    </div>

                    <div x-show="option === 1">
                        <textarea name="statement_text" :disabled="option !== 1" placeholder="@lang('entry.placeholder.statement')" class="w-full" cols="30"
                            rows="3"></textarea>
                    </div>

                    <div x-show="option === 2" x-cloak>
                        <x-dropzone-file el="statement" name="statement_file"></x-dropzone-file>
                    </div>
                </div>

                <div x-data="{ option: 1 }" class="flex flex-col gap-4" x-show="subject?.units" x-cloak>
                    <div class="flex gap-4">
                        <label>@lang('entry.label.solution')</label>

                        <div class="flex gap-2 items-center">
                            <button type="button" @click="option = 1"
                                :class="option === 1 ?
                                    'bg-gray-100 h-6 w-6 border border-zinc-200 p-1 rounded flex justify-center items-center' :
                                    'bg-white h-6 w-6 border border-zinc-200 p-1 rounded flex justify-center items-center hover:bg-gray-100'">
                                @svg('heroicon-o-pencil-square', 'h-4 w-4')

                            </button>

                            <button type="button" @click="option = 2"
                                :class="option === 2 ?
                                    'bg-gray-100 h-6 w-6 border border-zinc-200 p-1 rounded flex justify-center items-center' :
                                    'bg-white h-6 w-6 border border-zinc-200 p-1 rounded flex justify-center items-center hover:bg-gray-100'">
                                @svg('heroicon-o-code-bracket', 'h-4 w-4')
                            </button>
                        </div>
                    </div>

                    <div x-show="option === 1">
                        <textarea name="solution" :disabled="option !== 1" placeholder="@lang('entry.placeholder.solution')" class="w-full" cols="30"
                            rows="3"></textarea>
                    </div>

                    <div x-show="option === 2" x-cloak>
                        <x-code-editor name="solution" label="HTML Code" language="html" height="h-96"
                            :value="old('solution', '')" />
                    </div>
                </div>

                <input type="hidden" name="locale_id" value="1">
            </div>
            <input type="submit" value="@lang('entry.button.add')" class="btn mt-4">
        </div>
    </form>
</x-layout>

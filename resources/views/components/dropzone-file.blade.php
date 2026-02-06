@props(['el' => '', 'message' => __('entry.error.image')])

<div x-data="imageUpload(@js($message))"
    class="flex flex-col items-center justify-center h-64 w-60 aspect-square
       bg-zinc-200 border border-dashed border-slate-500 rounded-xl
       cursor-pointer hover:bg-gray-100 transition-all duration-200 p-4 hover:shadow-xl"
    @click="$refs.file.click()">
    <template x-if="preview">
        <img :src="preview" alt="Preview" class="w-full object-cover" />
    </template>

    <template x-if="!preview">
        <div class="flex flex-col items-center justify-center gap-2">
            @svg('heroicon-o-photo', 'h-10 w-10')
            <div>
                <p class="mb-2 text-sm">@lang('app.upload.click-or-drop')</p>
                <p class="text-xs">@lang('app.upload.size')</p>
            </div>
        </div>
    </template>

    <input x-ref="file" id="{{ $el }}" name="{{ $el }}" type="file"
        accept="image/png, image/jpeg" class="hidden" @change="pick" />
</div>

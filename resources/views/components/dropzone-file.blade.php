@props(['field' => $field ?? ''])

<div x-data="imageUpload('', true, 5)" class="relative">
    <div class="border-2 border-dashed border-gray-300 rounded-xl p-4 flex flex-col items-center justify-center gap-3 transition-colors duration-150"
        @dragover.prevent="dragover = true" @dragleave.prevent="dragover = false" @drop.prevent="handleDrop($event)"
        :class="{ 'bg-gray-100 border-sky-400': dragover }">
        @svg('heroicon-o-cloud-arrow-up', 'h-12 w-12 text-sky-400')
        <p class="font-semibold text-gray-700 text-center">Drag and drop files here or click Upload</p>
        <button type="button" @click="$refs.fileInput.click()"
            class="mt-2 bg-sky-500 text-white px-4 py-2 rounded-md hover:bg-sky-600 shadow">
            Upload
        </button>
    </div>

    <div class="mt-6 overflow-x-auto">
        <div class="flex gap-4 pb-2">
            <template x-for="(img, index) in previews" :key="img.name + index">
                <div draggable="true" @dragstart="startDrag(index)" @dragover.prevent="onDragOver(index)"
                    @dragend="endDrag()" @drop.prevent="endDrag()"
                    class="relative flex flex-col items-center border border-gray-200 rounded-lg p-2 bg-gray-50 shadow-sm flex-shrink-0 cursor-move transition-opacity"
                    :class="{ 'opacity-50': draggedIndex === index }" style="width: calc((100% - 3rem) / 2.5);">
                    <img :src="img.url" class="w-full h-32 object-cover rounded-lg pointer-events-none"
                        :alt="img.name" />
                    <span class="mt-1 text-xs text-gray-600 truncate w-full text-center" x-text="img.name"></span>
                    <button type="button" @click="removeImage(index)"
                        class="absolute top-1 right-1 bg-red-500 text-white rounded-full p-1 hover:bg-red-600">
                        @svg('heroicon-o-x-mark', 'h-4 w-4')
                    </button>
                </div>
            </template>
        </div>
    </div>

    <input type="file" x-ref="fileInput" :name="@js($field)" multiple class="hidden"
        @change="pick($event)">
</div>

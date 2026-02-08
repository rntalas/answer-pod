@props([
    'title' => __('unit.title', ['subject' => $subject->name, 'unit' => $unit->number]),
])

<x-layout :title="$title">
    <div class="flex flex-col gap-4">
        <p class="font-bold text-lg"> {{ $title }}</p>
        @if ($entries)
        @else
        @endif
    </div>

</x-layout>

@props([
    'name' => 'code',
    'value' => '',
    'language' => 'javascript',
    'height' => 'h-64',
])

<div x-data="codeMirrorEditor" x-init="$nextTick(() => setup('{{ $name }}', $el.dataset.value, '{{ $language }}'))" data-value="{{ base64_encode($value) }}" class="w-full">
    <div x-ref="editor" class="border rounded {{ $height }} w-full relative"></div>
    <input type="hidden" name="{{ $name }}" x-ref="hidden" value="{{ $value }}">
</div>

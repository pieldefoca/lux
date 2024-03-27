@props([
    'toolbar' => 'bold italic underline | bullet-list ordered-list',
])

<div
    x-data="tiptap('{{ $attributes->wire('model')->value }}', '{{ $toolbar }}', $wire)"
    wire:ignore
    {{ $attributes->whereDoesntStartWith('wire:model') }}
    class="border border-gray-300 rounded-md"
>
    <div class="flex items-center space-x-1 p-2 border-b border-gray-300">
        @foreach(explode(' ', $toolbar) as $button)
            @if($button === '|')
                <span class="opacity-30 !mx-2">|</span>
            @else
                <x-dynamic-component :component="'lux::input.tiptap.buttons.'.$button" />
            @endif
        @endforeach
    </div>

    <div x-ref="editor" class="tiptap"></div>
</div>

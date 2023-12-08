@php
    $page = lux()->currentPage();
@endphp

<x-layouts.app>
    <x-slot name="title">{{ $page->title }}</x-slot>
    <x-slot name="description">{{ $page->description }}</x-slot>

    {{ $slot }}

    @if(app()->isLocal())
        <livewire:lux-media-selector :$page />

        <script>
            document.querySelectorAll('img[src*="lux-image"]').forEach(el => {
                el.addEventListener('click', e => {
                    const key = el.getAttribute('src').split(':')[1]
                    Livewire.dispatch('lux-media', { key: key })
                })
            })
        </script>
    @endif
</x-layouts.app>

@php
    $page = lux()->currentPage();
@endphp

<x-layouts.app>
    <x-slot name="title">{{ $page->title }}</x-slot>
    <x-slot name="description">{{ $page->description }}</x-slot>

    {{ $slot }}
</x-layouts.app>

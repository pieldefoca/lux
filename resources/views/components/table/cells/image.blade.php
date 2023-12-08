@props([
    'media',
])

<x-lux::table.td {{ $attributes }}>
    <x-lux::media-preview type="image" :$media class="!w-16" />
</x-lux::table.td>
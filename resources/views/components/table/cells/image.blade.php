@props([
    'url',
])

<x-lux::table.td {{ $attributes }}>
    <x-lux::media-preview type="image" :$url class="!w-16" />
</x-lux::table.td>
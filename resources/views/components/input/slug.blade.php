@props(['prefix' => null, 'translatable' => false])

<x-lux::input.text translatable {{ $attributes }}>
    <x-slot name="inlineLeadingAddon">
        <span class="text-xs text-stone-500">{{ $prefix ?? config('app.url') . '/' }}</span>
    </x-slot>
</x-lux::input.text>
@props(['prefix' => null])

<x-lux::input.text {{ $attributes }}>
    <x-slot name="inlineLeadingAddon">
        <span class="text-xs text-stone-500">{{ $prefix ?? config('app.url') . '/' }}</span>
    </x-slot>
</x-lux::input.text>
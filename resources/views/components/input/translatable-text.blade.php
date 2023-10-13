@props([
    'label',
    'error' => '',
])

@php
    $locales = Admin\Models\Locale::all();
@endphp

<x-admin.input.group
    x-data="{
        expanded: false,
        toggle() { this.expanded = !this.expanded }
    }"
    label="Nombre"
    :error="$errors->first('name')"
>
    <div class="space-y-1">
        @foreach($locales as $locale)
        <div
            @if(!$locale->default) x-show="expanded" @endif
            x-bind:class="{'space-x-2': expanded}"
            class="flex items-center"
        >
            <div x-show="expanded" class="flex items-center justify-center space-x-1 px-2 py-1.5 w-14 bg-stone-100 rounded">
                <img src="{{ $locale->flagUrl }}" class="w-3 h-3" />
                <span class="text-[11px]">{{ $locale->code }}</span>
            </div>

            <x-admin.input.text-input wire:model="{{ $attributes->get('wire:model') }}.{{ $locale->code }}" />
        </div>
        @endforeach
    </div>

    <x-slot name="labelAddon">
        <button type="button" x-on:click="toggle" class="text-xs">Idiomas</button>
    </x-slot>
</x-admin.input.group>

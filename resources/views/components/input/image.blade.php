@aware([
    'translatable'
])

@props([
    'url' => null,
])

@php
$wireModel = $attributes->whereStartsWith('wire:model')->getAttributes();
$model = '';
foreach($wireModel as $attribute => $value) {
    $wireModel = $attribute;
    $model = $value;
}
@endphp

@foreach(Admin\Models\Locale::all() as $locale)
    <div x-data @class(['w-full', 'hidden' => $this->currentLocaleCode !== $locale->code,])>
        <div class="flex items-center space-x-8 w-full">
            <img @click="$refs.input.click()" src="{{ $url }}" class="w-52 aspect-square object-cover border border-stone-200 rounded-lg cursor-pointer transition-all duration-300 shadow hover:sepia" />

            <div class="flex-grow space-y-4">
                <x-admin.input.group label="Nombre">
                    <x-admin.input.text :translatable="false" wire:model="{{ $model }}.{{$locale->code}}.1" />
                </x-admin.input.group>
                <x-admin.input.group label="Alt">
                    <x-admin.input.text :translatable="false" wire:model="{{ $model }}.{{$locale->code}}.2" />
                </x-admin.input.group>
                <x-admin.input.group label="Title">
                    <x-admin.input.text :translatable="false" wire:model="{{ $model }}.{{$locale->code}}.3" />
                </x-admin.input.group>
            </div>
        </div>
        <div class="mt-2">
            <button
                @click="$refs.input.click()"
                type="button"
                class="px-2 py-px bg-teal-100 border border-teal-600 rounded text-teal-600 text-xs transition-colors duration-300 hover:text-teal-700 hover:border-teal-700"
            >
                Elegir imagen
            </button>
            <button
                @click="$wire.$set('{{$model}}.{{$locale->code}}', [])"
                type="button"
                class="px-2 py-px bg-red-100 border border-red-400 rounded text-red-400 text-xs transition-colors duration-300 hover:text-red-500 hover:border-red-500"
            >
                Eliminar imagen
            </button>
        </div>
        <input
            x-ref="input"
            type="file"
            wire:model="{{$model}}.{{$locale->code}}.0"
            style="display: none;"
        />
    </div>
@endforeach

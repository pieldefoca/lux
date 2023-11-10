<x-lux::card title="Información" class="p-4 space-y-6">
    <img src="{{ asset('uploads/goku.jpg') }}" class="w-24 aspect-square object-cover rounded-md" />

    <x-lux::input.group translatable label="Nombre">
        <x-lux::input.text wire:model="name" />
    </x-lux::input.group>

    <x-lux::input.group translatable label="Alt (SEO)">
        <x-lux::input.text wire:model="alt" />
    </x-lux::input.group>

    <x-lux::input.group translatable label="Título (SEO)">
        <x-lux::input.text wire:model="title" />
    </x-lux::input.group>
</x-lux::card>

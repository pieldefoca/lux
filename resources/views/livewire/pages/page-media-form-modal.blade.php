<x-lux::modal title="Nuevo elemento">
    <form wire:submit.prevent="save" class="space-y-6">
        <x-lux::input.inline-group
            required
            label="ID"
            error="{{ $errors->first('key') }}"
        >
            <x-lux::input.text wire:model.blur="key" />
        </x-lux::input.inline-group>

        <x-lux::input.inline-group
            required
            label="Archivo"
            error="{{ $errors->first('media') }}"
        >
            <x-lux::input.media wire:model="media" />
        </x-lux::input.inline-group>
    </form>

    <x-slot name="footer">
        <div class="flex items-center justify-end space-x-8 w-full">
            <x-lux::link wire:click="hide">Cancelar</x-lux::link>
            <x-lux::button wire:click="save" icon="device-floppy">Guardar</x-lux::button>
        </div>
    </x-slot>
</x-lux::modal>

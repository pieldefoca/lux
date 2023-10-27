<x-lux::modal title="{{ $editing ? 'Editar categoría del blog' : 'Nueva categoría del blog' }}">
    <form wire:submit="save" class="space-y-6">
        <x-lux::locale-selector />

        <x-lux::input.group
            translatable
            required
            label="Imagen"
            :error="$errors->first('image.*.0')"
        >
            <x-lux::input.media type="image" wire:model="image" />
        </x-lux::input.group>

        <x-lux::input.inline-group
            translatable
            required
            label="Nombre"
            error="{{ $errors->first('name.*') }}"
        >
            <x-lux::input.text wire:model.live="name" />
        </x-lux::input.inline-group>

        <x-lux::input.inline-group
            translatable
            required
            label="URL"
            error="{{ $errors->first('slug.*') }}"
        >
            <x-lux::input.text wire:model="slug" />
        </x-lux::input.inline-group>

        <x-lux::input.inline-group
            required
            label="Activa"
            error="{{ $errors->first('active') }}"
        >
            <x-lux::input.toggle wire:model="active" />
        </x-lux::input.inline-group>
    </form>

    <x-slot name="footer">
        <div class="flex items-center justify-end space-x-8 w-full">
            <x-lux::link wire:click="hide">Cancelar</x-lux::link>
            <x-lux::button type="submit" wire:click="save" icon="device-floppy">Guardar</x-lux::button>
        </div>
    </x-slot>
</x-lux::modal>

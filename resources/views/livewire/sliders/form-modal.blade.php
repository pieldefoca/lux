<x-lux::modal title="Nuevo slider">
    <form wire:submit.prevent="save" class="space-y-6">
        <x-lux::input.inline-group
            required
            label="Nombre"
            error="{{ $errors->first('name') }}"
        >
            <x-lux::input.text wire:model="name" />
        </x-lux::input.inline-group>

        <x-lux::input.inline-group
            required
            label="Posición"
            error="{{ $errors->first('position') }}"
        >
            <x-lux::input.select native wire:model="position">
                @foreach(Pieldefoca\Lux\Enum\SliderPosition::options() as $value => $label)
                    <option value="{{ $value }}">{{ $label }}</option>
                @endforeach
            </x-lux::input.select>
        </x-lux::input.inline-group>
    </form>

    <x-slot name="footer">
        <div class="flex items-center justify-end space-x-8 w-full">
            <x-lux::link wire:click="hide">Cancelar</x-lux::link>
            <x-lux::button wire:click="save" icon="device-floppy">Guardar</x-lux::button>
        </div>
    </x-slot>
</x-lux::modal>

<x-lux::modal title="Nuevo slider">
    <x-lux::modal.panel>
        <form wire:submit="save" class="space-y-6">
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

            <x-lux::modal.footer>
                <div class="flex items-center justify-end space-x-8 w-full">
                    <x-lux::modal.button.cancel />
                    <x-lux::modal.button.save />
                </div>
            </x-lux::modal.footer>
        </form>
    </x-lux::modal.panel>
</x-lux::modal>

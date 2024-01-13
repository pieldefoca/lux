<x-lux::modal>
    <x-lux::modal.panel>
        <x-lux::modal.title>{{ $editing ? 'Editar idioma' : 'Nuevo idioma' }}</x-lux::modal.title>

        <x-lux::locale-selector />

        <form wire:submit="save" class="mt-6">
            <div class="space-y-6">
                <x-lux::input.inline-group translatable required label="Nombre" error="{{ $errors->first('name.*') }}">
                    <x-lux::input.text wire:model="name" />
                </x-lux::input.inline-group>

                <x-lux::input.inline-group required label="Código" error="{{ $errors->first('code') }}">
                    <x-lux::input.text wire:model="code" />
                </x-lux::input.inline-group>
        
                <x-lux::input.inline-group required label="Activo" error="{{ $errors->first('active') }}">
                    <x-lux::input.checkbox wire:model="active" />
                </x-lux::input.inline-group>
            </div>
            
            <x-lux::modal.footer>
                <div class="flex items-center justify-end space-x-8 w-full">
                    <x-lux::modal.button.cancel />
                    <x-lux::modal.button.save />
                </div>
            </x-lux::modal.footer>
        </form>
    </x-lux::modal.panel>
</x-lux::modal>
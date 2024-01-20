<x-lux::modal :title="$this->editing ? 'Editar diapositiva' : 'Nueva diapositiva'">
    <x-lux::modal.panel>
        <form wire:submit="save" class="space-y-6">
            <x-lux::locale-selector />
    
            <x-lux::input.inline-group
                translatable
                required
                label="Fondo"
                help="Imagen: jpg, png, webp, svg, gif. Video: mp4, mov"
            >
                <x-lux::input.media wire:model="background" />
            </x-lux::input.inline-group>
    
            <x-lux::input.inline-group
                translatable
                label="Título"
                error="{{ $errors->first('title') }}"
            >
                <x-lux::input.text wire:model="title" />
            </x-lux::input.inline-group>
    
            <x-lux::input.inline-group
                translatable
                label="Subtítulo"
                error="{{ $errors->first('subtitle') }}"
            >
                <x-lux::input.text wire:model="subtitle" />
            </x-lux::input.inline-group>
    
            <x-lux::input.inline-group
                translatable
                label="Texto de acción"
                error="{{ $errors->first('action_text') }}"
            >
                <x-lux::input.text wire:model="action_text" />
            </x-lux::input.inline-group>
    
            <x-lux::input.inline-group
                translatable
                label="URL de acción"
                error="{{ $errors->first('action_link') }}"
            >
                <x-lux::input.url wire:model="action_link" />
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

<x-lux::modal>
    <x-lux::modal.panel size="sm">
        <x-lux::modal.title>{{ $this->editing ? 'Editar diapositiva' : 'Nueva diapositiva' }}</x-lux::modal.title>

        <form wire:submit="save">
            <x-lux::form-header without-locale />
    
            <div class="space-y-6 mb-12">
                <x-lux::input.group
                    translatable
                    required
                    label="Fondo"
                    help="Imagen: jpg, png, webp, svg, gif. Video: mp4, mov"
                >
                    <x-lux::input.media translatable wire:model="background" />
                </x-lux::input.group>

                @if($showTitle)
                    <x-lux::input.group translatable label="Título" error="{{ $errors->first('title') }}">
                        <x-lux::input.text wire:model="title" translatable />
                    </x-lux::input.group>
                @endif

                @if($showSubtitle)
                    <x-lux::input.group translatable label="Subtítulo" error="{{ $errors->first('subtitle') }}">
                        <x-lux::input.text wire:model="subtitle" translatable />
                    </x-lux::input.group>
                @endif

                @if($showAction)
                    <x-lux::input.group translatable label="Texto de acción" error="{{ $errors->first('action_text') }}">
                        <x-lux::input.text wire:model="action_text" translatable />
                    </x-lux::input.group>
                    <x-lux::input.group translatable label="URL de acción" error="{{ $errors->first('action_link') }}">
                        <x-lux::input.url wire:model="action_link" />
                    </x-lux::input.group>
                @endif
            </div>

            <x-lux::modal.footer>
                <div class="flex items-center justify-between space-x-8 w-full">
                    <x-lux::locale-selector />

                    <div class="flex items-center space-x-8">
                        <x-lux::modal.button.cancel />
                        <x-lux::modal.button.save />
                    </div>
                </div>
            </x-lux::modal.footer>
        </form>
    </x-lux::modal.panel>
</x-lux::modal>

<x-lux::slideover title="Información" class="p-4">
    <div class="space-y-4">
        <div class="flex items-center space-x-3 bg-amber-50 border border-amber-200 rounded p-2">
            <x-lux::tabler-icons.alert-triangle class="flex-grow w-8 h-8 text-amber-300" />
            <p class="text-[11px] text-amber-500">Ten en cuenta que los cambios que hagas aquí se aplicarán en todos los sitios en los que se esté utilizando esta imagen</p>
        </div>

        <x-lux::required-fields />

        <img src="{{ $media?->getUrl() }}" class="w-48 aspect-square object-cover rounded-md" />

        <x-lux::input.group required label="Nombre" :error="$errors->first('filename')">
            <x-lux::input.text wire:model.blur="filename" />
        </x-lux::input.group>

        <x-lux::input.group translatable label="Alt (SEO)">
            <x-lux::input.text translatable wire:model="alt" />
        </x-lux::input.group>

        <x-lux::input.group translatable label="Título (SEO)">
            <x-lux::input.text translatable wire:model="title" />
        </x-lux::input.group>

        <x-lux::input.group label="URL">
            <div x-data class="flex items-center space-x-2">
                <p x-ref="url" class="px-2 py-1 border border-stone-200 rounded text-xs">{{ $this->url }}</p>
                <button @click="navigator.clipboard.writeText($refs.url.innerText); $tooltip('Copiado')" type="button">
                    <x-lux::tabler-icons.copy class="w-5 h-5"/>
                </button>
            </div>
        </x-lux::input.group>
    </div>

    <x-slot name="footer">
        <div class="flex items-center justify-between">
            <div>
                @if($deletable)
                    <x-lux::button.icon 
                        x-on:click.stop.prevent="
                            Swal.fire({
                                title: 'Eliminar archivo',
                                text: '¿Seguro que quieres eliminar este archivo?',
                                icon: 'warning',
                                showCancelButton: true,
                                customClass: {
                                    confirmButton: 'px-4 py-2 rounded-lg border-2 border-red-500 bg-red-100 text-red-500 transition-colors duration-300 hover:bg-red-200 hover:border-red-600 hover:text-red-600',
                                    cancelButton: 'hover:underline',
                                    actions: 'space-x-6',
                                },
                                buttonsStyling: false,
                                confirmButtonText: 'Eliminar',
                                cancelButtonText: 'Cancelar',
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    $wire.call('deleteMedia')
                                }
                            })
                        "
                        action="delete" 
                    />
                @endif
            </div>

            <div class="flex items-center space-x-6">
                <x-lux::button.link x-on:click="$wire.visible = false">Cancelar</x-lux::button.link>
                <x-lux::button wire:click="save">Guardar</x-lux::button>
            </div>
        </div>
    </x-slot>
</x-lux::slideover>

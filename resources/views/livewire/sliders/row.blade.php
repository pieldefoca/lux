<x-lux::table.tr :model="$slider">
    <x-lux::table.td>{{ $slider->name }}</x-lux::table.td>
    <x-lux::table.td>{{ $slider->position }}</x-lux::table.td>
    <x-lux::table.td>
        <x-lux::table.row-actions>
            <x-lux::menu.item>
                <a href="{{ route('lux.sliders.edit', $slider) }}">
                    <div class="flex items-center space-x-2">
                        <x-lux::tabler-icons.edit class="w-5 h-5" />
                        <span>Editar</span>
                    </div>
                </a>
            </x-lux::menu.item>

            <x-lux::menu.item
                x-on:click.stop.prevent="
                    Swal.fire({
                        title: 'Eliminar slider',
                        text: '¿Seguro que quieres eliminar este slider?',
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
                            $wire.call('delete')
                        }
                    })
                "
                class="group"
            >
                <div class="flex items-center space-x-2 group-hover:text-red-400">
                    <x-lux::tabler-icons.trash class="w-5 h-5" />
                    <span>Eliminar</span>
                </div>
            </x-lux::menu.item>
        </x-lux::table.row-actions>
    </x-lux::table.td>
</x-lux::table.tr>

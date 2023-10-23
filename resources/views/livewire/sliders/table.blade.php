<x-lux::table.table>
    <x-slot name="head">
        <x-lux::table.th>Nombre</x-lux::table.th>
        <x-lux::table.th>Posición</x-lux::table.th>
        <x-lux::table.th>Acciones</x-lux::table.th>
    </x-slot>

    <x-slot name="body">
        @foreach($this->rows as $slider)
            <x-lux::table.tr>
                <x-lux::table.td>{{ $slider->name }}</x-lux::table.td>
                <x-lux::table.td>{{ $slider->position }}</x-lux::table.td>
                <x-lux::table.td no-padding>
                    <a href="{{ route('lux.sliders.edit', $slider) }}">
                        <x-lux::table.edit-button />
                    </a>
                </x-lux::table.td>
            </x-lux::table.tr>
        @endforeach
    </x-slot>
</x-lux::table.table>

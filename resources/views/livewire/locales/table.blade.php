<x-lux::table.table>
    <x-slot name="head">
        <x-lux::table.th sort="name">Nombre</x-lux::table.th>
        <x-lux::table.th>Icono</x-lux::table.th>
        <x-lux::table.th>Activo</x-lux::table.th>
        <x-lux::table.th></x-lux::table.th>
    </x-slot>

    <x-slot name="body">
        @foreach($this->rows as $locale)
            <x-lux::table.tr :model="$locale">
                <x-lux::table.td>{{ $locale->name }}</x-lux::table.td>
                <x-lux::table.td>
                    <img src="{{ $locale->flagUrl }}" class="w-5 h-5 rounded-full" />
                </x-lux::table.td>
                <x-lux::table.td>
                    <livewire:table.cells.toggle :model="$locale" field="active" message="Has actualizado el estado correctamente" :key="uniqid()" />
                </x-lux::table.td>
                <x-lux::table.td no-padding>
                    <div class="space-x-3">
                        <x-lux::table.edit-button @click="$dispatch('edit-locale', { locale: {{$locale->id}} })" />
                        <x-lux::table.delete-button />
                    </div>
                </x-lux::table.td>
            </x-lux::table.tr>
        @endforeach
    </x-slot>
</x-lux::table.table>

<x-lux::table.table>
    <x-slot name="head">
        <x-lux::table.th>Nombre</x-lux::table.th>
        <x-lux::table.th>Posición</x-lux::table.th>
        <x-lux::table.th class="w-20"></x-lux::table.th>
    </x-slot>

    <x-slot name="body">
        @foreach($this->rows as $slider)
            <livewire:sliders.row :$slider :$hasBulkActions :$reorderable :$locale :key="uniqid()" />
        @endforeach
    </x-slot>
</x-lux::table.table>

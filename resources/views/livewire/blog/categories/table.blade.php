<x-lux::table.table>
    <x-slot name="bulkActions">
        <x-lux::dropdown.item wire:click="activateSelected">Activar seleccionadas</x-lux::dropdown.item>
        <x-lux::dropdown.item wire:click="deactivateSelected">Desactivar seleccionadas</x-lux::dropdown.item>
    </x-slot>

    <x-slot name="head">
        <x-lux::table.th>Imagen</x-lux::table.th>
        <x-lux::table.th sort="title">Nombre</x-lux::table.th>
        <x-lux::table.th>Activa</x-lux::table.th>
        <x-lux::table.th>Acciones</x-lux::table.th>
    </x-slot>

    <x-slot name="body">
        @foreach($this->rows as $category)
            <x-lux::table.tr :model="$category" wire:key="category-{{$category->id}}">
                <x-lux::table.cells.image :url="$category->getImageUrl($currentLocaleCode)" />
                <x-lux::table.td>{{ $category->name }}</x-lux::table.td>
                <x-lux::table.td>
                    <livewire:table.cells.toggle 
                        :model="$category" 
                        field="active" 
                        message="🤙 Has actualizado el estado de la categoría correctamente"
                        :key="uniqid()"
                    />
                </x-lux::table.td>
                <x-lux::table.td>
                    <x-lux::table.edit-button x-on:click="$dispatch('edit-blog-category', { category: {{$category->id}} })" />
                </x-lux::table.td>
            </x-lux::table.tr>
        @endforeach
    </x-slot>
</x-lux::table.table>

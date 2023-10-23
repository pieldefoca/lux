<x-lux::table.table>
    <x-slot name="head">
        <x-lux::table.th sort="title">Título</x-lux::table.th>
        <x-lux::table.th>Estado</x-lux::table.th>
        <x-lux::table.th>Recomendado ⭐️</x-lux::table.th>
        <x-lux::table.th>Acciones</x-lux::table.th>
    </x-slot>

    <x-slot name="body">
        @foreach($this->rows as $post)
            <x-lux::table.tr :model="$post">
                <x-lux::table.td>{{ $post->title }}</x-lux::table.td>
                <x-lux::table.td>{{ $post->status->forHumans() }}</x-lux::table.td>
                <x-lux::table.td>
                    <livewire:table.cells.toggle 
                        :model="$post" 
                        field="featured" 
                        message="🤙 Has actualizado el estado del post correctamente"
                        :key="uniqid()"
                    />
                </x-lux::table.td>
                <x-lux::table.td no-padding>
                    <a href="{{ route('lux.sliders.edit', $post) }}">
                        <x-lux::table.edit-button />
                    </a>
                </x-lux::table.td>
            </x-lux::table.tr>
        @endforeach
    </x-slot>
</x-lux::table.table>

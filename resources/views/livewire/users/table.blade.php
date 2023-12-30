<x-lux::table.table>
    <x-slot name="head">
        <x-lux::table.th sort="username">Nombre de usuario</x-lux::table.th>
        <x-lux::table.th sort="name">Nombre</x-lux::table.th>
        <x-lux::table.th sort="email">Email</x-lux::table.th>
        <x-lux::table.th>Acciones</x-lux::table.th>
    </x-slot>

    <x-slot name="body">
        @foreach($this->rows as $user)
            <x-lux::table.tr :model="$user">
                <x-lux::table.td>{{ $user->username }}</x-lux::table.td>
                <x-lux::table.td>{{ $user->name }}</x-lux::table.td>
                <x-lux::table.td>{{ $user->email }}</x-lux::table.td>
                <x-lux::table.td no-padding>
                    <div class="space-x-3">
                        <a href="{{ route('lux.users.edit', $user) }}">
                            <x-lux::table.edit-button />
                        </a>
                        <x-lux::table.delete-button />
                    </div>
                </x-lux::table.td>
            </x-lux::table.tr>
        @endforeach
    </x-slot>
</x-lux::table.table>

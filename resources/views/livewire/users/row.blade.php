<x-lux::table.tr :model="$user">
    <x-lux::table.td>{{ $user->username }}</x-lux::table.td>
    <x-lux::table.td>{{ $user->name }}</x-lux::table.td>
    <x-lux::table.td>{{ $user->email }}</x-lux::table.td>
    <x-lux::table.td no-padding>
        <div class="space-x-3">
            <a href="{{ route('lux.users.edit', $user) }}">
                <x-lux::table.edit-button />
            </a>
            <x-lux::table.delete-button wire:click="delete" />
        </div>
    </x-lux::table.td>
</x-lux::table.tr>
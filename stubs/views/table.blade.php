<x-lux::table.table>
    <x-slot name="head">
        <x-lux::table.th sort="username"></x-lux::table.th>
    </x-slot>

    <x-slot name="body">
        @foreach($this->rows as $row)
            {{-- --}}
        @endforeach
    </x-slot>
</x-lux::table.table>

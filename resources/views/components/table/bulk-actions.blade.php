<x-lux::menu>
    <x-lux::menu.button class="px-3 py-1 border border-stone-200 rounded-md hover:bg-gray-100">
        <div class="flex items-center space-x-2">
            <span class="text-sm">Acciones</span>
            <x-lux::tabler-icons.selector class="w-4 h-4 opacity-50" />
        </div>
    </x-lux::menu.button>

    <x-lux::menu.items>
        {{ $slot }}
    </x-lux::menu.items>
</x-lux::menu>
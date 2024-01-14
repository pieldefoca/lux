<x-lux::menu>
    <x-lux::menu.button class="p-0.5 border-2 border-transparent rounded-lg opacity-40 transition-all duration-300 hover:opacity-100 hover:border-stone-800 hover:text-stone-800">
        <x-lux::tabler-icons.dots-vertical />
    </x-lux::menu.button>

    <x-lux::menu.items>
        {{ $slot }}
    </x-lux::menu.items>
</x-lux::menu>

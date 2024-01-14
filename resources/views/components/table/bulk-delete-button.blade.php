<button type="button" {{ $attributes->class(['flex items-center space-x-1 px-3 py-1 border border-red-300 bg-red-200 rounded-md text-red-500 text-sm transition-colors duration-300 hover:bg-red-500 hover:text-white']) }}>
    <x-lux::tabler-icons.trash class="w-4 h-4" />
    <span x-text="'Eliminar ' + $wire.selected.length"></span>
</button>

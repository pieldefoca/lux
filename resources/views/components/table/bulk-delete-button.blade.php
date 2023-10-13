<button type="button" {{ $attributes->class(['flex items-center space-x-1 px-3 py-1 bg-red-200 rounded-md text-red-500 text-xs transition-colors duration-300 hover:bg-red-500 hover:text-white']) }}>
    <x-lux::tabler-icons.trash class="w-5 h-5" />
    <span>Eliminar {{ count($this->selected) }}</span>
</button>

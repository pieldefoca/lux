<div class="group relative inline-block">
    <input
        type="text"
        {{
            $attributes->merge(['placeholder' => 'Buscar...'])
        }}
        class="w-72 px-2 py-2 outline-none text-xs border border-stone-300 rounded-md transition-colors duration-300 focus:border-stone-500 focus:ring-0 hover:border-stone-400"
    />

    <x-lux::tabler-icons.search class="absolute top-1/2 right-3 -translate-y-1/2 w-5 h-5 opacity-50 transition-opacity group-hover:opacity-100" />
</div>
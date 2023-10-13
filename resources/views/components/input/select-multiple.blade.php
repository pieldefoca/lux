@props([
    'options',
    'placeholder' => 'Selecciona las opciones',
])

<div
    x-data="{
        value: @entangle($attributes->wire('model')),
        options: @js($options),
        open: false,
        select(value) {
            this.value.push(value)
        },
        unselect(value) {
            const index = this.value.indexOf(value)
            if (index > -1) this.value.splice(index, 1)
        },
        get selectedOptions() {
            return this.options.filter(option => this.value.includes(option.value))
        },
        get filteredOptions() {
            return this.options.filter(option => !this.value.includes(option.value))
        }
    }"
    x-on:click.outside="open = false"
    class="relative w-full"
>
    <div class="px-3 border-2 border-stone-200 rounded-lg">
        <button
            x-on:click="open = !open"
            type="button"
            class="flex items-center justify-between w-full py-2 text-left text-xs hover:border-stone-800"
        >
            <span>{{ $placeholder }}</span>
            <x-tabler-icons.chevron-down x-bind:class="{'rotate-180': open}" class="w-5 h-5 text-stone-400 transition-transform duration-100" />
        </button>

        <template x-if="selectedOptions.length">
            <div class="flex items-center space-x-2 py-2 border-t border-stone-300">
                <template x-for="option in selectedOptions">
                    <div class="flex items-center space-x-2 px-2 py-1 rounded-md bg-stone-700 text-xs text-stone-100">
                        <span x-text="option.label"></span>
                        <button x-on:click="unselect(option.value)" type="button">
                            <x-tabler-icons.circle-minus class="w-4 h-4 transition-colors duration-300 hover:text-red-400" />
                        </button>
                    </div>
                </template>
            </div>
        </template>
    </div>

    <div x-show="open" x-transition class="absolute top-full left-0 w-full max-h-80 mt-1 border border-stone-200 bg-white shadow rounded-lg overflow-x-hidden z-10">
        <div x-show="filteredOptions.length > 0">
            <template x-for="option in filteredOptions">
                <button
                    x-text="option.label"
                    x-on:click="select(option.value)"
                    type="button"
                    class="w-full px-3 py-2 text-left text-xs transition-colors duration-300 hover:bg-stone-800 hover:text-stone-100"
                >
                </button>
            </template>
        </div>
        <div x-show="filteredOptions.length === 0" class="flex flex-col items-center py-4">
            <x-tabler-icons.input-search class="w-16 h-16 opacity-10" />
            <span class="text-sm text-stone-600">No hay opciones</span>
        </div>
    </div>
</div>

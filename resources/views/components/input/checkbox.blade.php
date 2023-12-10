@props([
    'label' => null,
])

<button
    type="button"
    x-data="{
        checked: false,
        init() {
            this.checked = this.$refs.input.checked
        }
    }"
    x-on:click="$refs.input.click()"
    class="group flex items-center space-x-1"
>
    <div
        x-bind:class="{'border-stone-800 bg-stone-800': checked, 'border-stone-400 group-hover:bg-stone-100': !checked}"
        class="flex items-center justify-center w-4 h-4 border rounded cursor-pointer transition-colors duration-100"
    >
        <x-lux::tabler-icons.check x-bind:class="{'hidden': !checked, 'block': checked}" class="w-4 h-4 text-white" />
    </div>
    @if($label)
        <label class="text-xs cursor-pointer">{{ $label }}</label>
    @endif
    <input x-ref="input" @change="checked = $el.checked" type="checkbox" {{ $attributes }} />
</button>

@props([
    'label' => '',
])

<div
    x-data="{
        checked: false,
        init() {
            this.checked = this.$refs.input.checked
        },
        toggle() {
            this.checked = !this.checked
            this.$refs.input.click()
        }
    }"
>
    <button type="button" x-on:click="toggle" class="group flex items-center space-x-1">
        <div
            x-bind:class="{'border-stone-800 bg-stone-800': checked, 'border-stone-400 group-hover:bg-stone-100': !checked}"
            class="flex items-center justify-center w-4 h-4 border rounded cursor-pointer transition-colors duration-100"
        >
            <x-lux::tabler-icons.check x-show="checked" class="w-4 h-4 text-white" />
        </div>
        <label class="relative text-xs cursor-pointer after:absolute after:bottom-0 after:left-0 after:h-px after:bg-stone-300 after:w-0 after:transition-all after:duration-300 group-hover:after:w-full">{{ $label }}</label>
    </button>

    <input x-ref="input" type="checkbox" {{ $attributes }} style="display: none;" />
</div>

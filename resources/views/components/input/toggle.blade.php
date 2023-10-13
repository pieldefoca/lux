<div
    x-data="{
        checked: false,
        init() {
            $nextTick(() => {
                this.checked = this.$refs.input.checked
            })
        },
        toggle() {
            this.checked = !this.checked
            this.$refs.input.click()
        }
    }"
>
    <button
        type="button"
        x-on:click="toggle"
        x-bind:class="{'border-stone-300 bg-stone-100': !checked, 'border-stone-800 bg-stone-200': checked}"
        class="relative w-12 h-[2rem] py-[2px] px-[2px]  rounded-full border-2 border-stone-300"
    >
        <div
            x-bind:class="{'left-[1.15rem] bg-stone-800': checked, 'left-[2px] bg-stone-300': !checked}"
            class="absolute top-[2px] w-6 h-[1.55rem] rounded-full transition-all duration-300"
        ></div>
    </button>
    <input x-ref="input" type="checkbox" {{ $attributes }} style="display: none;" />
</div>

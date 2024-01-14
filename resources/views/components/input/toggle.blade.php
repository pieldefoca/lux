<div class="lux-toggle">
    <input x-ref="input" type="checkbox" {{ $attributes }} style="display: none;" />

    <button
        type="button"
        x-on:click="$refs.input.click()"
        class="relative w-12 h-[2rem] py-[2px] px-[2px] rounded-full border-2 border-stone-300"
    >
        <div class="absolute top-[2px] w-6 h-[1.55rem] rounded-full transition-all duration-300"></div>
    </button>
</div>

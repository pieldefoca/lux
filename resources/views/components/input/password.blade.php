<div
    x-data="{
        type: 'password',
    }"
    class="relative w-full"
>
    <input
        x-bind:type="type"
        {{ $attributes }}
        class="
            w-full px-2 py-2 pr-12 rounded-md border border-stone-300 outline-none text-sm transition-colors duration-300
            focus:border-black
            hover:border-black
        "
    />

    <button type="button" x-on:click="type = type === 'password' ? 'text' : 'password'" class="absolute top-1/2 right-3 -translate-y-1/2 text-stone-400 transition-colors duration-300 hover:text-stone-600">
        <x-lux::tabler-icons.eye x-show="type === 'password'" class="w-6 h-6" />
        <x-lux::tabler-icons.eye-off x-show="type === 'text'" class="w-6 h-6" />
    </button>
</div>

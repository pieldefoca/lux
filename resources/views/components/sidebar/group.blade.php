@props([
    'label',
    'active',
])

<li
    x-data="{
        open: @js($active),
    }"
    class="!mt-4"
>
    <button
        @click="open = !open"
        type="button"
        class="flex items-center justify-between w-full mb-2 text-stone-400 transition-colors duration-300 hover:text-stone-500"
    >
        <span class="text-xs uppercase">{{ $label }}</span>
        <x-lux::tabler-icons.chevron-down x-bind:class="{'rotate-180': open}" class="w-4 h-4 transition-all origin-center" />
    </button>

    <ul x-show="open" x-collapse class="space-y-1">
        {{ $slot }}
    </ul>
</li>

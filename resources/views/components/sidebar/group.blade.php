@props([
    'label',
    'icon' => null,
    'active',
])

<li
    x-data="{
        open: @js($active),
    }"
>
    <button
        @click="open = !open"
        type="button"
        class="flex items-center justify-between w-full px-2 py-1.5 mb-2 rounded-md transition-colors hover:bg-black hover:text-white"
    >
        <div class="flex items-center space-x-2">
            @if($icon)
                <x-dynamic-component component="lux::tabler-icons.{{$icon}}" class="w-5 h-5" />
            @endif
            <span>{{ $label }}</span>
        </div>
        <x-lux::tabler-icons.chevron-down x-bind:class="{'rotate-180': open}" class="w-4 h-4 transition-all origin-center" />
    </button>

    <ul x-show="open" x-collapse class="space-y-1 border-l border-gray-300 ml-4 pl-1">
        {{ $slot }}
    </ul>
</li>

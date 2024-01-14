<button
    x-menu:item
    x-bind:class="{
        'bg-slate-100 text-gray-900': $menuItem.isActive,
        'text-gray-600': ! $menuItem.isActive,
        'opacity-50 cursor-not-allowed': $menuItem.isDisabled,
    }"
    {{ $attributes->merge(['type' => 'button'])->class(['flex text-sm items-center gap-2 w-full px-3 py-1.5 text-left whitespace-nowrap hover:bg-slate-50 disabled:text-gray-500 transition-colors']) }}
>
    {{ $slot }}
</button>

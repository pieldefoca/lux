<a
    x-menu:item
    href="javascript:void(0)"
    {{ $attributes }}
    :class="{
        'bg-black text-white': $menuItem.isActive,
        'text-gray-600': ! $menuItem.isActive,
        'opacity-50 cursor-not-allowed': $menuItem.isDisabled,
    }"
    class="block w-full px-3 py-1 text-xs transition-colors"
>
    {{ $slot }}
</a>
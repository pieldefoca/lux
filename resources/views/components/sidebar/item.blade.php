@props([
    'link',
    'icon',
    'active' => false,
])

<li
    @class([
        'rounded-md transition-colors duration-300 hover:bg-black hover:text-white',
        'bg-accent text-white' => $active,
    ])
>
    <a href="{{ $link }}" class="flex items-center space-x-2 px-2 py-1.5">
        @if($icon)
            <x-dynamic-component component="lux::tabler-icons.{{$icon}}" class="w-5 h-5" />
        @endif

        <span>{{ $slot }}</span>
    </a>
</li>

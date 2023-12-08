@props(['link', 'icon' => null])

<a
    href="{{ $link ?? 'javascript:void(0)' }}"
    {{
        $attributes->class([
            'relative flex items-center space-x-2 text-stone-700 text-sm',
            'after:absolute after:bottom-0 after:left-0 after:w-0 after:h-px after:bg-stone-800 after:transition-all after:duration-300',
            'hover:after:w-full'
        ])
    }}
>
    @if($icon)
    <x-dynamic-component component="lux::tabler-icons.{{$icon}}" class="w-5 h-5" />
    @endif

    <span>{{ $slot }}</span>
</a>

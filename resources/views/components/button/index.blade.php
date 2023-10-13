@props([
    'color' => 'primary', // primary, secondary, danger
    'icon' => null,
])

<button
    {{
        $attributes
            ->merge(['type' => 'button'])
            ->class([
                'flex items-center space-x-2 px-6 py-2 rounded-md text-sm transition-colors duration-300',
                'bg-stone-800 text-stone-100 hover:bg-stone-700' => $color === 'primary',
                'bg-white border border-stone-200 text-stone-700 shadow hover:bg-stone-800 hover:text-stone-100' => $color === 'secondary',
                'border-2 border-red-500 bg-red-200 text-red-600 hover:bg-red-500 hover:text-stone-100' => $color === 'danger',
            ])
    }}
>
    @if($icon)
        <x-dynamic-component component="lux::tabler-icons.{{$icon}}" class="w-5 h-5" />
    @endif

    <span>{{ $slot }}</span>
</button>

@props([
    'noPadding' => false,
])

<td
    {{ 
        $attributes->class([
            'px-4 text-sm border-b border-stone-100',
            'py-3' => !$noPadding,
        ]) 
    }}
>
    {{ $slot }}
</td>

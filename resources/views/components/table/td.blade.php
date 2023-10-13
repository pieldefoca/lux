@props([
    'noPadding' => false,
])

<td
    @class([
        'px-4 text-sm border-b border-stone-200',
        'py-3' => !$noPadding,
    ])
>
    {{ $slot }}
</td>

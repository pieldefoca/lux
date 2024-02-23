@props([
    'title' => null,
])

<div
    {{
        $attributes->class([
            'w-full rounded-lg border border-gray-100 shadow-sm bg-white',
        ])
    }}
>
    <div
        @class([
            'flex items-center justify-between',
            'border-b border-stone-200 pb-4 mb-2' => $title || isset($actions),
        ])
    >
        @if($title)
            <h1 class="text-xl">{{ $title }}</h1>
        @endif

        <div>
            {{ $actions ?? '' }}
        </div>
    </div>

    {{ $slot }}
</div>

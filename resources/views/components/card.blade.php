@props([
    'title' => null,
])

<div
    {{
        $attributes->class([
            'w-full rounded-lg border border-line-color shadow-sm bg-card-bg-color',
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

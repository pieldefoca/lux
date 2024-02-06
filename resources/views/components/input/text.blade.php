@props([
    'compact' => false, 
    'translatable' => false,
])

<div class="group relative flex w-full rounded-md border border-stone-300 overflow-hidden transition-all duration-200 hover:border-stone-400 has-[:focus]:ring-2 has-[:focus]:ring-stone-600 has-[:focus]:border-transparent">
    <div @class([
        'grid place-items-center px-2 bg-stone-200 border-r border-stone-300 text-xs', 
        'hidden' => !isset($leadingAddon)
    ])>
        {{ $leadingAddon ?? '' }}
    </div>

    <div @class([
        'grow-0 shrink-0 basis-auto grid place-items-center px-2 bg-stone-100 text-sm transition-colors duration-300', 
        'hidden' => !isset($inlineLeadingAddon)
    ])>
        {{ $inlineLeadingAddon ?? '' }}
    </div>

    @if($translatable)
        @php
            $wireModelData = $attributes->whereStartsWith('wire:model')->getAttributes();
            $wireModelValue = reset($wireModelData);
            $wireModelName = key($wireModelData);
        @endphp
        @foreach(Pieldefoca\Lux\Models\Locale::all() as $locale)
            <input
                type="text"
                {{ $wireModelName }}="{{ $wireModelValue }}.{{ $locale->code }}"
                @class([
                    'flex-auto w-full px-2 py-1.5 border-none rounded-md text-sm transition-colors duration-300 outline-none focus:ring-transparent',
                    'hidden' => $this->locale !== $locale->code,
                ])
            />
        @endforeach
    @else
        <input
            type="text"
            {{ $attributes }}
            class="flex-auto w-full px-2 py-1.5 border-none rounded-md text-sm transition-colors duration-300 outline-none focus:ring-transparent"
        />
    @endif

    <div @class([
        'grid place-items-center px-2 border-l border-stone-300 bg-stone-200 text-xs transition-colors duration-300', 
        'hidden' => !isset($trailingAddon)
    ])>
        {{ $trailingAddon ?? '' }}
    </div>

    <div @class([
        'grid place-items-center px-2 bg-stone-100 text-sm transition-colors duration-300 group-hover:bg-white', 
        'hidden' => !isset($inlineTrailingAddon)
    ])>
        {{ $inlineTrailingAddon ?? '' }}
    </div>
</div>

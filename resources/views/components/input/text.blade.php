@props([
    'compact' => false, 
    'leadingAddon' => null,
    'trailingAddon' => null,
    'inlineLeadingAddon' => null,
    'inlineTrailingAddon' => null,
])
@aware(['translatable' => false])

@php
$locales = $translatable
    ? Pieldefoca\Lux\Models\Locale::all()
    : [Pieldefoca\Lux\Models\Locale::default()];
@endphp

@foreach($locales as $locale)
    <div
        x-data="{ focused: false }"
        @class([
            'group relative flex w-full rounded-md overflow-hidden transition-all duration-300 ',
            'hidden' => $translatable && $this->currentLocaleCode !== $locale->code,
        ])
        :class="{
            'ring-1 ring-stone-300 hover:ring-black': !focused,
            'ring-1 ring-black': focused,
        }"
    >
        @if($leadingAddon)
            <div class="grid place-items-center px-2 bg-stone-200 border-r border-stone-300 text-xs">
                {{ $leadingAddon }}
            </div>
        @endif

        @if($inlineLeadingAddon)
            <div x-ref="inlineLeadingAddon" class="grow-0 shrink-0 basis-auto grid place-items-center px-2 bg-stone-100 text-sm transition-colors duration-300">
                {{ $inlineLeadingAddon }}
            </div>
        @endif

        <input
            x-ref="input"
            type="text"
            @focus="focused = true"
            @blur="focused = false"
            @if($translatable)
                {{ $attributes->localizedWireModel($locale->code) }}
            @else
                {{ $attributes->whereStartsWith('wire:model') }}
            @endif
            {{
                $attributes->whereDoesntStartWith('wire:model')->class([
                    'flex-auto w-full px-2 py-2 border-none rounded-md text-sm transition-colors duration-300 outline-none',
                ])
            }}
        />

        @if($trailingAddon)
            <div 
                x-ref="trailingAddon" 
                class="grid place-items-center px-2 border-l border-stone-300 bg-stone-200 text-xs transition-colors duration-300"
            >
                {{ $trailingAddon }}
            </div>
        @endif

        @if($inlineTrailingAddon)
            <div x-ref="inlineTrailingAddon" class="grid place-items-center px-2 bg-stone-100 text-sm transition-colors duration-300 group-hover:bg-white">
                {{ $inlineTrailingAddon }}
            </div>
        @endif
    </div>
@endforeach

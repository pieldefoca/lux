@props([
    'compact' => false, 
    'leadingAddOn' => null,
    'trailingAddOn' => null,
    'inlineLeadingAddOn' => null,
    'inlineTrailingAddOn' => null,
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
            'ring-1 ring-stone-300 hover:ring-2 hover:ring-stone-500 hover:border-stone-500': !focused,
            'ring-2 ring-stone-500': focused,
        }"
    >
        @if($leadingAddOn)
            <div class="grid place-items-center px-2 bg-stone-200 border-r border-stone-300 text-xs">
                {{ $leadingAddOn }}
            </div>
        @endif

        @if($inlineLeadingAddOn)
            <div x-ref="inlineLeadingAddOn" class="grid place-items-center pl-2 bg-stone-100 text-sm transition-colors duration-300 group-hover:bg-white">
                {{ $inlineLeadingAddOn }}
            </div>
        @endif

        <input
            x-ref="input"
            type="text"
            @focus="focused = true"
            @blur="focused = false"
            @if($translatable)
                {{ $attributes->localizedWireModel($locale->code) }}
            @endif
            {{
                $attributes->class([
                    'w-full px-2 py-2 bg-stone-100 text-sm transition-colors duration-300 outline-none hover:bg-white',
                ])
            }}
        />

        @if($trailingAddOn)
            <div 
                x-ref="trailingAddOn" 
                class="grid place-items-center px-2 border-l border-stone-300 bg-stone-200 text-xs transition-colors duration-300"
            >
                {{ $trailingAddOn }}
            </div>
        @endif

        @if($inlineTrailingAddOn)
            <div x-ref="inlineTrailingAddOn" class="grid place-items-center px-2 bg-stone-100 text-sm transition-colors duration-300 group-hover:bg-white">
                {{ $inlineTrailingAddOn }}
            </div>
        @endif
    </div>
@endforeach

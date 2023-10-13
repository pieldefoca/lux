@aware([
    'translatable' => false,
    'compact' => false,
])

@php
$wireModel = $attributes->whereStartsWith('wire:model')->getAttributes();
$model = '';
foreach($wireModel as $attribute => $value) {
    $wireModel = $attribute;
    $model = $value;
}

$locales = $translatable
    ? Pieldefoca\Lux\Models\Locale::all()
    : [Pieldefoca\Lux\Models\Locale::default()];
@endphp

@foreach($locales as $locale)
    <div
        @class([
            'flex w-full rounded-md border border-stone-300 overflow-hidden',
            'hidden' => $translatable && $this->currentLocaleCode !== $locale->code,
        ])
    >
        @isset($leadingAddOn)
            <div x-ref="leadingAddOn" class="grid place-items-center px-2 bg-stone-200 border-r border-stone-300 text-xs">
                https://
            </div>
        @endisset

        <input
            x-ref="input"
            type="text"
            @if($translatable)
            {{$wireModel}}="{{$model}}.{{$locale->code}}"
            @else
            {{$wireModel}}="{{$model}}"
            @endif
            {{
                $attributes->whereDoesntStartWith('wire:model')
                    ->class([
                        'w-full px-2 py-1 bg-stone-100 outline-none text-sm transition-colors duration-300',
                        'focus:border-stone-500 focus:bg-white',
                        'hover:bg-white hover:border-stone-400',
                        'px-2 py-2 rounded-md text-sm' => !$compact,
                        'p-1 rounded-md text-xs' => $compact,
                    ])
            }}
        />
    </div>
@endforeach

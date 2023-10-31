@aware(['translatable' => false])

@php
$locales = $translatable
    ? Pieldefoca\Lux\Models\Locale::all()
    : [Pieldefoca\Lux\Models\Locale::default()];
@endphp

@foreach($locales as $locale)
    <textarea
        @if($translatable)
            {{ $attributes->localizedWireModel($locale->code) }}
        @endif
        {{
            $attributes->class([
                'w-full px-2 py-2 rounded-md border border-stone-300 outline-none bg-stone-100 text-sm transition-colors duration-300 focus:bg-white focus:border-stone-500 hover:bg-white hover:border-stone-400',
                'hidden' => $translatable && $this->currentLocaleCode !== $locale->code,
            ])
        }}
    ></textarea>
@endforeach

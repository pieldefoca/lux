<div
    @class([
        'inline-flex flex-col space-y-2 px-4 py-2 mb-4 bg-white border border-stone-200 rounded-lg shadow',
        'hidden' => !$this->hasMultipleLocales,
    ])
>
    <div class="flex items-center space-x-4">
        <p class="text-sm text-stone-700">Idioma</p>

        <div class="flex items-center space-x-3">
            @foreach(Pieldefoca\Lux\Models\Locale::all() as $locale)
                <button
                    type="button"
                    wire:click="selectLocale('{{ $locale->code }}')"
                    @class([
                        'relative flex items-center space-x-1 px-4 py-1 rounded-md transition-colors duration-300',
                        'bg-stone-800 text-stone-100' => $this->currentLocaleCode === $locale->code,
                        'bg-stone-100 hover:bg-stone-800 hover:text-stone-100' => $this->currentLocaleCode !== $locale->code,
                    ])
                >
                    <img src="{{ $locale->flagUrl }}" alt="{{ $locale->code }}" class="w-4 h-4" />
                    <span class="text-sm uppercase">{{ $locale->code }}</span>
                    @if($locale->default)
                        <div class="absolute top-1 right-1 w-1.5 h-1.5 rounded-full bg-orange-500"></div>
                    @endif
                </button>
            @endforeach
        </div>
    </div>

    <p class="flex items-center space-x-1 text-stone-500 text-xs">
        <span class="w-1.5 h-1.5 rounded-full bg-orange-500"></span>
        <span>Idioma por defecto</span>
    </p>
</div>

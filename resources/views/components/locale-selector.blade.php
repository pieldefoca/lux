<div
    @class([
        'inline-flex items-center space-x-4 px-4 py-2 mb-4 bg-white border border-stone-200 rounded-lg shadow',
        'hidden' => !$this->hasMultipleLocales,
    ])
>
    <p class="text-sm text-stone-700">Idioma</p>

    <div class="flex items-center space-x-3">
        @foreach(Pieldefoca\Lux\Models\Locale::all() as $locale)
            <button
                type="button"
                wire:click="selectLocale('{{ $locale->code }}')"
                @class([
                    'flex items-center space-x-1 px-2 py-1 rounded-md transition-colors duration-300',
                    'bg-stone-800 text-stone-100' => $this->currentLocaleCode === $locale->code,
                    'bg-stone-100 hover:bg-stone-800 hover:text-stone-100' => $this->currentLocaleCode !== $locale->code,
                ])
            >
                <img src="{{ $locale->flagUrl }}" alt="{{ $locale->code }}" class="w-4 h-4" />
                <span class="text-sm uppercase">{{ $locale->code }}</span>
            </button>
        @endforeach
    </div>
</div>

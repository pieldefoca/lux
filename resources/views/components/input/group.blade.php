@props([
    'label' => null,
    'help' => '',
    'error' => '',
    'required' => false,
    'translatable' => false,
])

<div class="flex flex-col" {{ $attributes }}>
    <div class="flex flex-col mb-px">
        @if($label)
        <div class="flex items-center justify-between">
            <div @class(['flex items-center space-x-2', 'mb-1' => $required || $translatable])>
                <label class="text-xs font-bold text-stone-600">{{ $label }}</label>

                @if($required || $translatable)
                    <div class="flex items-center space-x-1">
                        @if($required)
                        <span class="px-1 py-px bg-blue-100 rounded text-[8px] text-stone-700">Obligatorio</span>
                        @endif

                        @if($translatable)
                        <span class="px-1 py-px bg-yellow-100 rounded border border-yellow-200" title="Campo traducible">
                            <x-lux::tabler-icons.language class="w-3 h-3" />
                        </span>
                        @endif
                    </div>
                @endif
            </div>

            <div>
                {{ $labelAddon ?? '' }}
            </div>
        </div>
        @endif

        <span class="text-[10px] text-stone-400">{{ $help }}</span>
    </div>

    <div class="flex items-center">
        @if($translatable && $this->hasMultipleLocales)
        <div class="flex items-center justify-center space-x-1 mr-2 px-2 py-1.5 w-14 bg-stone-100 rounded">
            <img src="{{ $this->currentLocale->flagUrl }}" class="w-3 h-3" />
            <span class="text-[11px]">{{ $this->currentLocale->code }}</span>
        </div>
        @endif

        {{ $slot }}
    </div>

    <span class="text-xs text-red-400">{{ $error }}</span>
</div>

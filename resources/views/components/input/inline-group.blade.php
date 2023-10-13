@props([
    'label' => null,
    'help' => '',
    'error' => '',
    'required' => false,
    'translatable' => false,
])

<div class="grid grid-cols-3 pb-6 border-b border-stone-200" {{ $attributes }}>
    <div class="flex flex-col mb-px">
        @if($label)
            <div class="flex items-center justify-between">
                <div @class(['flex flex-col space-y-1', 'mb-1' => $required || $translatable])>
                    <label
                        @class([
                            'mt-1 text-[11px] font-bold text-stone-600',
                            'mt-2' => !$required && !$translatable,
                        ])
                    >{{ $label }}</label>

                    @if($required || $translatable)
                        <div class="flex items-center space-x-1">
                            @if($translatable && $this->hasMultipleLocales)
                                <div class="flex items-center justify-center space-x-1 px-1 py-px bg-stone-100 border border-stone-200 rounded">
                                    <img src="{{ $this->currentLocale->flagUrl }}" class="w-2 h-2" />
                                    <span class="text-[8px]">{{ $this->currentLocale->code }}</span>
                                </div>
                            @endif

                            @if($required)
                                <span class="px-1 py-px bg-blue-100 border border-blue-200 rounded text-[8px] text-stone-700">Obligatorio</span>
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

    <div class="col-span-2 flex items-center">
        {{ $slot }}
    </div>

    <span class="text-xs text-red-400">{{ $error }}</span>
</div>

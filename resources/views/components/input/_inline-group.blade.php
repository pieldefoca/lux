@props([
    'label' => null,
    'help' => '',
    'error' => '',
    'required' => false,
    'translatable' => false,
    'danger' => false,
])

<div class="grid grid-cols-3 items-start gap-x-8 pb-6 border-b border-stone-200 last:border-b-0" {{ $attributes }}>
    <div class="flex flex-col mb-px">
        @if($label)
            <div class="flex flex-col space-y-1">
                <div @class(['flex flex-col space-y-1', 'mb-1' => $required || $translatable])>
                    <label
                        @class([
                            'mt-1 font-bold text-stone-600',
                            'mt-2' => !$required && !$translatable,
                        ])
                    >{{ $label }}</label>

                    @if($required || $translatable)
                        <div class="flex items-center space-x-1">
                            @if($required)
                                <span class="px-1 py-px bg-blue-100 border border-blue-200 rounded text-[8px] text-stone-700">Obligatorio</span>
                            @endif

                            @if($translatable)
                                <span class="px-1 py-px bg-yellow-100 rounded border border-yellow-200" title="Campo traducible">
                                    <x-lux::tabler-icons.language class="w-3 h-3" />
                                </span>

                                @if($this->hasMultipleLocales)
                                    <div class="flex items-center justify-center space-x-1 px-1 py-px bg-stone-100 border border-stone-200 rounded">
                                        <img src="{{ $this->currentLocale->flagUrl }}" class="w-2 h-2" />
                                        <span class="text-[8px]" x-text="$store.lux.locale"></span>
                                    </div>
                                @endif
                            @endif
                        </div>
                    @endif
                </div>

                <div>
                    {{ $labelAddon ?? '' }}
                </div>
            </div>
        @endif

        @if($danger)
            <p class="flex items-center space-x-1 text-xs text-orange-400">
                <x-lux::tabler-icons.alert-circle class="w-4 h-4" />
                <span>{{ trans('lux::lux.input-danger') }}</span>
            </p>
        @endif

        <span class="text-xs text-stone-400">{{ $help }}</span>
    </div>

    <div class="col-span-2 flex items-center mt-3">
        {{ $slot }}
    </div>

    <span class="text-xs text-red-400">{{ $error }}</span>
</div>

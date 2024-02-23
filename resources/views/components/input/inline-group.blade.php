@props([
    'for' => null,
    'label' => null,
    'help' => null,
    'error' => null,
    'required' => false,
    'translatable' => false,
])

<div
    @class(['group', 'has-error' => $error])
>
    <div class="flex items-center space-x-2 mb-1">
        @if($label)
            <label for="{{ $for }}" @class(['text-sm font-bold', 'text-red-600' => $error])>{{ $label }}</label>
        @endif

        @if($required)
            <span>
                <x-lux::tabler-icons.asterisk class="w-2 h-2 text-red-600" />
            </span>
        @endif

        @if($translatable)
            <div class="flex items-center space-x-1 px-2 border border-gray-300 rounded text-[12px]">
                <img :src="`/vendor/lux/img/flags/${$store.lux.locale}.svg`" class="w-3 h-3" />
                <span x-text="$store.lux.locale"></span>
            </div>
        @endif
    </div>

    <div>
        {{ $slot }}
    </div>

    @if($help)
        <div>
            <span class="text-sm font-light opacity-50">{{ $help }}</span>
        </div>
    @endif

    @if($error)
        <div>
            <span class="text-sm text-red-400">{{ $error }}</span>
        </div>
    @endif
</div>

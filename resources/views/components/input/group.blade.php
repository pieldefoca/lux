@props([
    'for' => null,
    'label' => null,
    'help' => null,
    'error' => null,
    'required' => false,
    'translatable' => false,
    'danger' => false,
])

<div
    @class(['group', 'has-error' => $error])
>
    <div class="flex items-center justify-between space-x-2 mb-1">
        <div class="flex items-center space-x-2">
            @if($label)
                <label for="{{ $for }}" @class(['text-sm font-bold', 'text-red-600' => $error])>{{ $label }}</label>
            @endif
    
            @if($required)
                <span>
                    <x-lux::tabler-icons.asterisk class="w-2 h-2 text-red-600" />
                </span>
            @endif
    
            @if($translatable)
                <div class="flex items-center space-x-1 px-2 border border-gray-300 rounded text-[10px]">
                    <img src="{{ asset("vendor/lux/img/flags/{$locale}.svg") }}" class="w-2 h-2" />
                    <span>{{ $locale }}</span>
                </div>
            @endif
        </div>

        <div class="flex items-center space-x-4">
            @if($danger)
                <p class="flex items-center space-x-1 text-xs text-orange-400">
                    <x-lux::tabler-icons.alert-circle class="w-4 h-4" />
                    <span>{{ trans('lux::lux.input-danger') }}</span>
                </p>
            @endif

            @isset($labelAddon)
                <div>
                    {{ $labelAddon }}
                </div>
            @endisset
        </div>
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

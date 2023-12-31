@props([
    'backRoute',
    'saveAction' => 'save',
    'errors',
])

<div class="flex items-center space-x-8">
    @if($errors->any())
        <p class="flex items-center space-x-1 text-red-400">
            <x-lux::tabler-icons.exclamation-circle />
            <span>{{ trans('lux::lux.fix-errors') }}</span>
        </p>
    @endif

    <x-lux::link :link="$backRoute">{{ trans('lux::lux.cancel') }}</x-lux::link>
    <x-lux::button.save wire:click="{{ $saveAction }}" />
</div>

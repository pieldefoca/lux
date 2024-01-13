<div>
    <x-slot name="title">{{ trans('lux::lux.locales-index-title') }}</x-slot>
    <x-slot name="subtitle">{{ trans('lux::lux.locales-index-subtitle') }}</x-slot>

    <x-slot name="actions">
        <x-lux::button @click="$dispatch('new-locale')" icon="square-rounded-plus">{{ trans('lux::lux.new-locale') }}</x-lux::button>
    </x-slot>

    <div>
        <livewire:locales.table />
    </div>

    <livewire:locales.form-modal />
</div>

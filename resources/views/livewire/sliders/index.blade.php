<div>
    <x-slot name="title">{{ trans('lux::lux.sliders-titulo') }}</x-slot>
    <x-slot name="subtitle">{{ trans('lux::lux.sliders-subtitulo') }}</x-slot>

    <x-slot name="actions">
        <x-lux::button x-on:click="$dispatch('new-slider')" icon="square-rounded-plus">{{ trans('lux::lux.new-slider') }}</x-lux::button>
    </x-slot>

    <div>
        <livewire:sliders.table />
    </div>

    <livewire:sliders.form-modal />
</div>
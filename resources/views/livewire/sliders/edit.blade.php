<x-lux::admin-page>
    <x-lux::title-bar title="Editar slider" subtitle="Estás editando el slider {{ $slider->name }}" />

    <div class="flex-grow">
        <livewire:sliders.form :$slider @updated="$refresh" />
    </div>

    <x-lux::action-bar without-locale centered>
        <x-lux::link :link="route('lux.sliders.index')">{{ trans('lux::lux.cancel') }}</x-lux::link>
        <x-lux::button.save wire:click="$dispatch('save-slider')" />
    </x-lux::action-bar>

    <livewire:sliders.slide-form-modal :$slider />
</x-lux::admin-page>

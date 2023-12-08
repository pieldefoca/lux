<div>
    <x-slot name="title">Editar slider</x-slot>
    <x-slot name="subtitle">Estás editando el slider "{{ $slider->name }}"</x-slot>

    <x-slot name="actions">
        <div class="flex items-center space-x-8">
            <x-lux::link :link="route('lux.sliders.index')">Cancelar</x-lux::link>
            <x-lux::button x-on:click="$dispatch('save-slider')" icon="device-floppy">Guardar</x-lux::button>
        </div>
    </x-slot>

    <livewire:sliders.form :$slider />

    <livewire:sliders.slide-form-modal :$slider />

    <livewire:media-manager.selector />
    <livewire:media-manager.slideover />
</div>

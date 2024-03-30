<div>
    <x-lux::title-bar :title="trans('lux::sliders.edit_slider')" :subtitle="trans('lux::sliders.you_are_editing', ['name' => $slider->name])" />

    <div class="flex-grow min-h-screen">
        <livewire:sliders.form :$slider @updated="$refresh" />
    </div>

    <x-lux::action-bar>
        <div class="flex items-center space-x-8">
            <a href="{{ route('lux.sliders.index') }}">
                <x-lux::button.link :link="route('lux.sliders.index')">{{ trans('lux::lux.cancel') }}</x-lux::button.link>
            </a>
            <x-lux::button.save wire:click="$dispatch('save-slider')" />
        </div>
    </x-lux::action-bar>

    <livewire:sliders.slide-form-modal :$slider />
</div>

<div>
    <x-lux::title-bar :title="trans('lux::lux.sliders_index_title')" :subtitle="trans('lux::lux.sliders_index_subtitle')" />

    <div class="flex-grow mt-8">
        <livewire:sliders.table />
    </div>

    <livewire:sliders.form-modal />
</div>
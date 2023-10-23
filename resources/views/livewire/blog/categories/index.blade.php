<div>
    <x-slot name="title">{{ trans('lux::lux.categorias-blog-titulo') }}</x-slot>
    <x-slot name="subtitle">{{ trans('lux::lux.categorias-blog-subtitulo') }}</x-slot>

    <x-slot name="actions">
        <x-lux::button x-on:click="$dispatch('new-blog-category')" icon="square-rounded-plus">{{ trans('lux::lux.nueva-categoria') }}</x-lux::button>
    </x-slot>

    <div>
        <livewire:blog.categories.table />
    </div>

    <livewire:blog.categories.form-modal />
</div>
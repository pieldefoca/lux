<div>
    <x-slot name="title">{{ trans('lux::lux.posts-titulo') }}</x-slot>
    <x-slot name="subtitle">{{ trans('lux::lux.posts-subtitulo') }}</x-slot>

    <x-slot name="actions">
        {{-- <a href="{{ route('lux.blog.posts.create') }}"> --}}
            <x-lux::button icon="square-rounded-plus">{{ trans('lux::lux.nuevo-post') }}</x-lux::button>
        {{-- </a> --}}
    </x-slot>

    <div>
        <livewire:blog.posts.table />
    </div>
</div>
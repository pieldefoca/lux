<div>
    <x-slot name="title">{{ trans('lux::lux.paginas-titulo') }}</x-slot>
    <x-slot name="subtitle">{{ trans('lux::lux.paginas-subtitulo') }}</x-slot>

    <div class="space-y-3">
        @foreach($this->pages as $page)
            <livewire:pages.card :$page key="page-{{$page->id}}-card"/>
        @endforeach
    </div>
</div>
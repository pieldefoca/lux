<div>
    <x-slot name="title">{{ trans('lux::lux.pages-index-title') }}</x-slot>
    <x-slot name="subtitle">{{ trans('lux::lux.pages-index-subtitle') }}</x-slot>

    <div class="max-w-3xl mx-auto">
        <x-lux::locale-selector />

        <x-lux::card class="p-6">
            <div class="space-y-3">
                @foreach($this->pages as $page)
                    <x-lux::pages.card :$page />
                @endforeach
            </div>
        </x-lux::card>
    </div>
</div>
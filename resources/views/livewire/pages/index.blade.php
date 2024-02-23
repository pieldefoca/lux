<x-lux::admin-page>
    <x-lux::title-bar :title="trans('lux::lux.pages-index-title')" :subtitle="trans('lux::lux.pages-index-subtitle')" />

    <x-lux::locale-selector />

    <div class="flex-grow w-1/2 max-w-3xl mx-auto">
        <x-lux::card class="mt-8 p-6">
            <div class="space-y-3">
                @foreach($this->pages as $page)
                    <x-lux::pages.card :$page />
                @endforeach
            </div>
        </x-lux::card>
    </div>
</x-lux::admin-page>
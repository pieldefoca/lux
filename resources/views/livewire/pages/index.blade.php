<div>
    <x-lux::title-bar :title="trans('lux::pages.index_title')" :subtitle="trans('lux::pages.index_subtitle')"></x-lux::title-bar>

    <div class="my-12 space-y-4">
        @foreach($pages as $page)
            <div class="flex items-center justify-between w-full border border-gray-300 bg-white p-2 pr-6 rounded-lg transition-all duration-300 hover:shadow">
                <div class="flex items-center space-x-6">
                    <img src="{{ $page->screenshotUrl }}" class="w-36 aspect-video rounded-md" />
                    
                    <div class="space-y-3">
                        <p class="text-xl font-bold">{{ $page->name }}</p>
                        <p class="text-gray-400">{{ $page->localizedUrl($this->locale) }}</p>
                    </div>
                </div>

                <div>
                    <a href="{{ route('lux.pages.edit', $page) }}">Editar</a>
                </div>
            </div>
        @endforeach
    </div>
</div>
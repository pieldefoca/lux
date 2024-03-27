<div>
    <x-lux::title-bar :title="trans('lux::pages.index_title')" :subtitle="trans('lux::pages.index_subtitle')"></x-lux::title-bar>

    <div class="my-12 mx-auto space-y-4 max-w-4xl">
        @foreach($pages as $page)
            <a href="{{ route('lux.pages.edit', $page) }}" class="flex items-center justify-between w-full border border-gray-300 bg-white p-2 pr-6 rounded-lg transition-all duration-300 hover:shadow-lg">
                <div class="flex items-center space-x-6">
                    <img src="{{ $page->screenshotUrl }}" class="w-36 aspect-video rounded-md"  alt="{{ $page->title }}"/>
                    
                    <div class="space-y-3">
                        <p class="text-xl font-bold">{{ $page->name }}</p>
                        <p class="text-gray-400">{{ $page->localizedUrl($this->locale) }}</p>
                    </div>
                </div>

                <div>
                    <div class="flex items-center space-x-3">
                        <x-lux::button.link x-on:click.stop.prevent="window.open('{{ route($page->id) }}', '_blank')">{{ trans('lux::pages.view_page') }}</x-lux::button.link>

                        <x-lux::button.link>{{ trans('lux::lux.edit') }}</x-lux::button.link>
                    </div>
                </div>
            </a>
        @endforeach
    </div>
</div>
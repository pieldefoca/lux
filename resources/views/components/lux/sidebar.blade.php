<ul class="space-y-2">
    <x-lux::sidebar.item :link="route('lux.dashboard')" icon="home-2" :active="Route::is('lux.dashboard')">
        {{ trans('lux::lux.dashboard') }}
    </x-lux::sidebar.item>

    <x-lux::sidebar.item :link="route('lux.pages.index')" icon="file-code" :active="Route::is('lux.pages.*')">
        {{ trans('lux::lux.pages') }}
    </x-lux::sidebar.item>
</ul>
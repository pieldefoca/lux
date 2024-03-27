<div>
    <x-lux::title-bar :title="trans('lux::lux.admin_panel')" :subtitle="trans('lux::lux.hello')" />

	<div class="w-full pt-36 grid place-items-center">
        <div class="flex flex-col items-center space-y-12 p-8 border border-gray-300 bg-white rounded-xl shadow-xl">
            <a href="/">
                <img src="{{ asset(config('lux.logo')) }}" class="w-64" />
            </a>

            <p class="text-2xl">{{ trans('lux::lux.admin_panel') }}</p>
        </div>
    </div>
</div>

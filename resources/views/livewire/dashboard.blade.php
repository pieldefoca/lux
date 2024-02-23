<x-lux::admin-page>
    <x-lux::title-bar :title="trans('lux::lux.admin-panel')" :subtitle="trans('lux::lux.hello')" />

	<div class="w-full pt-36 grid place-items-center">
        <div class="flex flex-col items-center space-y-12 p-8 border border-stone-300 rounded-xl shadow-xl">
            <a href="/">
                <img src="{{ asset(config('lux.logo')) }}" class="w-64" />
            </a>

            <p class="text-2xl">{{ trans('lux::lux.admin-panel') }}</p>
        </div>
    </div>
</x-lux::admin-page>

<div>
	<x-slot name="title">{{ trans('lux::lux.admin-panel') }}</x-slot>
	<x-slot name="subtitle">{{ trans('lux::lux.hello') }}</x-slot>

	<div class="w-full pt-36 grid place-items-center">
        <div class="flex flex-col items-center space-y-12 p-8 border border-stone-300 rounded-xl shadow-xl">
            <img src="{{ asset(config('lux.logo')) }}" class="w-64" />

            <p class="text-2xl">{{ trans('lux::lux.admin-panel') }}</p>
        </div>
    </div>
</div>

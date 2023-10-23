@props([
    'type' => 'any', // any, image, video
])

@aware(['translatable' => false])

@php
$wireModel = $attributes->whereStartsWith('wire:model')->getAttributes();
$model = '';
foreach($wireModel as $attribute => $value) {
    $wireModel = $attribute;
    $model = $value;
}
$imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];
$videoExtensions = ['mp4', 'mov', 'wmv', 'webm', 'avi', 'flv', 'mkv'];
$locales = $translatable
    ? Pieldefoca\Lux\Models\Locale::all()
    : [Pieldefoca\Lux\Models\Locale::default()];
@endphp

@foreach($locales as $locale)
    @php
        $field = $translatable ? $attributes->wire('model').'.'.$locale->code : $attributes->wire('model');
        $url = $translatable ? $this->$model[$locale->code][4] : $this->$model[4];
        $fileExtension = pathinfo($url, PATHINFO_EXTENSION);
        $isImage = $url && str($fileExtension)->startsWith($imageExtensions);
        $isVideo = $url && str($fileExtension)->startsWith($videoExtensions);
    @endphp
    <div 
        x-data
        @class(['@container w-full', 'hidden' => $this->currentLocaleCode !== $locale->code,])
    >
        <div class="flex items-center space-x-6 w-full">
            <x-lux::media-preview :$type :$url x-on:click="$refs.input.click()" />

            <div class="flex-grow space-y-2">
                <x-lux::input.group label="Nombre">
                    <x-lux::input.text :translatable="false" wire:model="{{ $translatable ? $model.'.'.$locale->code.'.1' : $model.'.1' }}" />
                </x-lux::input.group>
                <x-lux::input.group label="Alt">
                    <x-lux::input.text :translatable="false" wire:model="{{ $translatable ? $model.'.'.$locale->code.'.2' : $model.'.2' }}" />
                </x-lux::input.group>
                <x-lux::input.group label="Title">
                    <x-lux::input.text :translatable="false" wire:model="{{ $translatable ? $model.'.'.$locale->code.'.3' : $model.'.3' }}" />
                </x-lux::input.group>
            </div>
        </div>
        <div class="mt-2">
            <button
                @click="$refs.input.click()"
                type="button"
                class="px-2 py-px bg-teal-100 border border-teal-600 rounded text-teal-600 text-xs transition-colors duration-300 hover:text-teal-700 hover:border-teal-700"
            >
                @if($type === 'any')
                    {{ trans('lux::lux.elegir-fichero') }}
                @elseif($type === 'image')
                    {{ trans('lux::lux.elegir-imagen') }}
                @elseif($type === 'video')
                    {{ trans('lux::lux.elegir-video') }}
                @endif
            </button>
            <button
                wire:click="clearMediaField('{{$model}}')"
                type="button"
                class="px-2 py-px bg-red-100 border border-red-400 rounded text-red-400 text-xs transition-colors duration-300 hover:text-red-500 hover:border-red-500"
            >
                @if($type === 'any')
                    {{ trans('lux::lux.eliminar-fichero') }}
                @elseif($type === 'image')
                    {{ trans('lux::lux.eliminar-imagen') }}
                @elseif($type === 'video')
                    {{ trans('lux::lux.eliminar-video') }}
                @endif
            </button>
        </div>
        <input
            x-ref="input"
            type="file"
            @if($translatable)
                wire:model="{{$model}}.{{$locale->code}}.0"
            @else
                wire:model="{{$model}}.0"
            @endif
            style="display: none;"
        />
    </div>
@endforeach

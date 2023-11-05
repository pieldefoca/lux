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
$splits = str($model)->explode('.');
$index = null;
if(count($splits) > 1) {
    $model = $splits[0];
    $index = $splits[1];
}
$imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];
$videoExtensions = ['mp4', 'mov', 'wmv', 'webm', 'avi', 'flv', 'mkv'];
$locales = $translatable
    ? Pieldefoca\Lux\Models\Locale::all()
    : [Pieldefoca\Lux\Models\Locale::default()];
@endphp

@foreach($locales as $locale)
    @php
        $url = '';
        $nameWireModel = '';
        $altWireModel = '';
        $titleWireModel = '';
        if($translatable) {
            $url = is_null($index) ? $this->$model[$locale->code][4] : $this->$model[$index][$locale->code][4];
            $nameWireModel = is_null($index) ? $model.'.'.$locale->code.'.1' : $model.'.'.$index.'.'.$locale->code.'.1';
            $altWireModel = is_null($index) ? $model.'.'.$locale->code.'.2' : $model.'.'.$index.'.'.$locale->code.'.2';
            $titleWireModel = is_null($index) ? $model.'.'.$locale->code.'.3' : $model.'.'.$index.'.'.$locale->code.'.3';
        } else {
            $url = $this->$model[4];
            $nameWireModel = $model.'.1';
            $altWireModel = $model.'.2';
            $titleWireModel = $model.'.3';
        }
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
                    <x-lux::input.text :translatable="false" wire:model="{{ $nameWireModel }}" />
                </x-lux::input.group>
                <x-lux::input.group label="Alt">
                    <x-lux::input.text :translatable="false" wire:model="{{ $altWireModel }}" />
                </x-lux::input.group>
                <x-lux::input.group label="Title">
                    <x-lux::input.text :translatable="false" wire:model="{{ $titleWireModel }}" />
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
                wire:model="{{ is_null($index) ? $model.'.'.$locale->code.'.0' : $model.'.'.$index.'.'.$locale->code.'.0' }}"
            @else
                wire:model="{{$model}}.0"
            @endif
            style="display: none;"
        />
    </div>
@endforeach

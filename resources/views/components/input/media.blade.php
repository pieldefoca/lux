@props([
    'type' => 'any', // any, image, video, file
    'ignoreTranslations' => false,
    'multiple' => false,
])

@aware(['translatable'])

@php
    use Pieldefoca\Lux\Models\Locale;
    use Pieldefoca\Lux\Models\Media;

    $defaultLocale = Locale::default();
    $defaultLocaleCode = $defaultLocale->code;
    $model = $attributes->wire('model')->value;
    $selectedIds = [];
    if($translatable) {
        $selectedIds = $ignoreTranslations ? $this->$model[$defaultLocaleCode] : $this->$model[$this->currentLocaleCode];
    } else {
        $selectedIds = $this->$model;
    }
    $selectedMedia = Media::whereIn('id', $selectedIds)->get();
    $locales = $translatable ? Locale::all() : [Locale::default()];
@endphp

@foreach($locales as $locale)
    <div 
        x-data="{
            select() {
                $wire.dispatch('select-media', { 
                    field: '{{ $model }}',
                    preSelected: {{ json_encode($selectedIds) }}, 
                    multiple: {{ json_encode($multiple) }} 
                })
            }
        }" 
        x-ref="media"
        @class(['hidden' => $translatable && $this->currentLocaleCode !== $locale->code])
    >
        <div class="flex flex-col space-y-3">
            @if((!$multiple && $selectedMedia->isEmpty()) || $multiple)
                @php
                    $icon = 'file-invoice';
                    $text = 'Inspeccionar';
                    if($type === 'any') {
                        $icon = 'photo-video';
                        $text = $multiple ? ($selectedMedia->isEmpty() ? 'Elige los archivos' : 'Añadir archivos') : 'Elige un archivo';
                    } elseif($type === 'image') {
                        $icon = 'photo';
                        $text = $multiple ? ($selectedMedia->isEmpty() ? 'Elige las imágenes' : 'Añadir imágenes') : 'Elige una imagen';
                    } elseif($type === 'video') {
                        $icon = 'movie';
                        $text = $multiple ? ($selectedMedia->isEmpty() ? 'Elige los vídeos' : 'Añadir vídeos') : 'Elige un vídeo';
                    } elseif($type === 'file') {
                        $icon = 'file-invoice';
                        $text = $multiple ? ($selectedMedia->isEmpty() ? 'Elige los archivos' : 'Añadir archivos') : 'Elige un archivo';
                    }
                @endphp
                <div class="flex items-center space-x-4">
                    <x-lux::button x-on:click="select" icon="{{ $icon }}">{{ $text }}</x-lux::button>
                    @if($multiple && $selectedMedia->isNotEmpty())
                        <button @click="$wire.clearMedia('{{ $model }}')" type="button" class="flex items-center space-x-1 text-xs transition-colors duration-300 hover:text-red-400">
                            <x-lux::tabler-icons.trash class="w-4 h-4" />
                            <span>Eliminar todo</span>
                        </button>
                    @endif
                </div>
            @endif
            @if($selectedMedia->isNotEmpty())
                <div class="flex flex-wrap space-x-4">
                    @foreach($selectedMedia as $media)
                        <x-lux::media-preview 
                            :model="$model"
                            :media="$media"
                            :type="$type"
                            :selectable="!$multiple"
                            editable
                            :unselectable="$multiple"
                        />
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endforeach
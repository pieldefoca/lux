@props([
    'type' => 'any', // any, image, video, file
    'multiple' => false,
    'required' => false,
])

@php
    $model = $attributes->wire('model')->value;
    $selectedIds = $this->$model;
    if(!is_array($selectedIds)) $selectedIds = [$selectedIds];
    $mediaSelected = !empty($selectedIds);
@endphp

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
>
    <div class="flex flex-col space-y-3 mt-4">
        @if((!$multiple && !$mediaSelected) || $multiple)
            @php
                $icon = 'file-invoice';
                $text = 'Inspeccionar';
                if($type === 'any') {
                    $icon = 'photo-video';
                    $text = $multiple ? (!$mediaSelected ? 'Elige los archivos' : 'Añadir archivos') : 'Elige un archivo';
                } elseif($type === 'image') {
                    $icon = 'photo';
                    $text = $multiple ? (!$mediaSelected ? 'Elige las imágenes' : 'Añadir imágenes') : 'Elige una imagen';
                } elseif($type === 'video') {
                    $icon = 'movie';
                    $text = $multiple ? (!$mediaSelected ? 'Elige los vídeos' : 'Añadir vídeos') : 'Elige un vídeo';
                } elseif($type === 'file') {
                    $icon = 'file-invoice';
                    $text = $multiple ? (!$mediaSelected ? 'Elige los archivos' : 'Añadir archivos') : 'Elige un archivo';
                }
            @endphp
            <div class="flex items-center space-x-4">
                <x-lux::button x-on:click="select" icon="{{ $icon }}">{{ $text }}</x-lux::button>
                @if($multiple && $mediaSelected)
                    <button 
                        x-on:click.stop.prevent="
                            Swal.fire({
                                title: 'Eliminar todos los archivos',
                                text: '¿Seguro que quieres eliminar todos los archivos?',
                                icon: 'warning',
                                showCancelButton: true,
                                customClass: {
                                    confirmButton: 'px-4 py-2 rounded-lg border-2 border-red-500 bg-red-100 text-red-500 transition-colors duration-300 hover:bg-red-200 hover:border-red-600 hover:text-red-600',
                                    cancelButton: 'hover:underline',
                                    actions: 'space-x-6',
                                },
                                buttonsStyling: false,
                                confirmButtonText: 'Eliminar archivos',
                                cancelButtonText: 'Cancelar',
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    $wire.clearMedia('{{ $model }}')
                                }
                            })
                        "
                        type="button" 
                        class="flex items-center space-x-1 text-xs transition-colors duration-300 hover:text-red-400"
                    >
                        <x-lux::tabler-icons.trash class="w-4 h-4" />
                        <span>Eliminar todo</span>
                    </button>
                @endif
            </div>
        @endif
        @if($mediaSelected)
            <div @if($multiple) drag-root="reorderGallery" drag-params="{{$model}}" @endif class="flex flex-wrap gap-4">
                @foreach($selectedIds as $mediaId)
                    <x-lux::media-preview 
                        :model="$model"
                        :media="$mediaId"
                        :type="$type"
                        :selectable="! $multiple"
                        editable
                        :unselectable="$multiple"
                        :clearable="! $required"
                        draggable
                    />
                @endforeach
            </div>
        @endif
    </div>
</div>
@props([
    'type' => 'any', // any, image, video
    'model' => null,
    'media' => null,
    'key' => null,
    'selectable' => false,
    'editable' => false,
    'unselectable' => false,
    'removable' => false,
    'swappable' => false,
    'clearable' => false,
    'withoutTypeIcon' => false,
    'draggable' => false,
])

@aware(['translatable'])

@php
if(is_numeric($media)) {
    $media = Pieldefoca\Lux\Models\Media::find($media);
}
$url = $media?->getThumbUrl();
$imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];
$videoExtensions = ['mp4', 'mov', 'wmv', 'webm', 'avi', 'flv', 'mkv'];
$fileExtension = pathinfo($url, PATHINFO_EXTENSION);
$isImage = $url && str($fileExtension)->startsWith($imageExtensions);
$isVideo = $url && str($fileExtension)->startsWith($videoExtensions);
$hasActions = $selectable || $editable || $unselectable || $removable || $swappable || $clearable;
@endphp

<div @if($draggable) draggable="true" drag-item="{{ $media->id }}" @endif class="flex space-x-0.5">
    <div
        {{
            $attributes->class([
                'relative grid place-items-center w-36 aspect-square bg-stone-100 border border-stone-200 rounded-lg overflow-hidden transition-all duration-300 shadow',
                'cursor-pointer hover:contrast-50' => $selectable,
                'cursor-grab' => $draggable,
            ])
        }}
        @if($selectable)
            @click="select"
        @endif
    >
        @if(is_null($media))
            @if($type === 'any')
                <x-lux::tabler-icons.photo-video class="w-1/2 h-1/2 opacity-20" />
            @elseif($type === 'image')
                <x-lux::tabler-icons.photo class="w-1/2 h-1/2 opacity-20" />
            @elseif($type === 'video')
                <x-lux::tabler-icons.movie class="w-1/2 h-1/2 opacity-20" />
            @endif
        @else
            @if($media->isImage())
                <div @class(['absolute top-0.5 left-0.5 p-0.5 rounded bg-black/60 text-white', 'hidden' => $withoutTypeIcon])>
                    <x-lux::tabler-icons.photo class="w-3 h-3" />
                </div>
                <img src="{{ $media->getThumbUrl() }}" class="w-full aspect-square object-cover" />
            @elseif($media->isVideo())
                <div class="absolute top-0.5 left-0.5 p-0.5 rounded bg-black/60 text-white">
                    <x-lux::tabler-icons.movie class="w-3 h-3" />
                </div>
                <video src="{{ $media->getUrl() }}" muted autoplay loop class="w-full aspect-square object-cover" />
            @elseif($media->isPdf())
                <div class="absolute top-0.5 left-0.5 p-0.5 rounded bg-black/60 text-white">
                    <x-lux::tabler-icons.file-type-pdf class="w-3 h-3" />
                </div>
                <div class="flex flex-col items-center justify-center space-y-2 w-full bg-stone-100 aspect-square rounded">
                    <x-lux::tabler-icons.file-type-pdf class="w-12 h-12 text-stone-400" />
                    <p class="text-[9px]">{{ $media->filename }}</p>
                </div>
            @else
                <div class="absolute top-0.5 left-0.5 p-0.5 rounded bg-black/60 text-white">
                    <x-lux::tabler-icons.file-invoice class="w-3 h-3" />
                </div>
                <div class="flex flex-col items-center justify-center space-y-2 w-full bg-stone-100 aspect-square rounded">
                    <x-lux::tabler-icons.file-invoice class="w-12 h-12 text-stone-400" />
                    <p class="text-[9px]">{{ $media->filename }}</p>
                </div>                        
            @endif
        @endif
    </div>

    @if($hasActions)
    <div class="flex flex-col space-y-3 mt-0.5">
        <div
            x-data="{
                open: false,
            }"
            @click.outside="open = false"
            class="relative"
        >
            <button @click="open = !open" type="button">
                <x-lux::tabler-icons.dots-circle-horizontal class="w-5 h-5 transition-all duration-300 hover:text-sky-500 hover:scale-125" />
            </button>
            
            <ul x-show="open" x-transition class="absolute top-full right-0 w-48 border border-stone-300 bg-white rounded shadow overflow-hidden">
                @if($selectable)
                    <li>
                        <button 
                            @click="select; open = false" 
                            type="button"
                            class="group/select flex items-center space-x-2 px-4 py-2 w-full transition-colors duration-300 hover:bg-black hover:text-white"
                        >
                            <x-lux::tabler-icons.hand-click class="w-4 h-4 transition-transform duration-300 group-hover/select:scale-125" />
                            <span>Cambiar imagen</span>
                        </button>
                    </li>
                @endif

                @if($swappable)
                    <li>
                        <button 
                            @click="$wire.swapMedia({{ $media->id }}, '{{ $key }}'); open = false" 
                            type="button"
                            class="group/swap flex items-center space-x-2 px-4 py-2 w-full transition-colors duration-300 hover:bg-black hover:text-white"
                        >
                            <x-lux::tabler-icons.hand-click class="w-4 h-4 transition-transform duration-300 group-hover/swap:scale-125" />
                            <span>Cambiar imagen</span>
                        </button>
                    </li>
                @endif

                @if($editable)
                    <li>
                        <button 
                            @click="$dispatch('edit-media', { media: {{ $media->id }} }); open = false"
                            type="button" 
                            class="group/edit flex items-center space-x-2 px-4 py-2 w-full transition-colors duration-300 hover:bg-black hover:text-white"
                        >
                            <x-lux::tabler-icons.edit class="w-4 h-4 transition-transform duration-300 group-hover/edit:scale-125" />
                            <span>Editar detalles</span>
                        </button>
                    </li>
                @endif

                @if($unselectable || $clearable)
                    <button 
                        @if($unselectable)
                            @click="$wire.unselectMedia('{{ $translatable ? $model.'.'.$this->locale : $model }}', {{ $media->id}}); open = false"
                        @else
                            @click="$wire.clearMediaField('{{ $translatable ? $model.'.'.$this->locale : $model }}'); open = false" 
                        @endif
                        type="button"
                        class="group flex items-center space-x-2 px-4 py-2 w-full transition-colors duration-300 hover:bg-black hover:text-white"
                    >
                        <x-lux::tabler-icons.square-x class="w-4 h-4 transition-transform duration-300 group-hover:scale-125" />
                        @if($unselectable)
                            <span>Deseleccionar</span>
                        @else
                            <span>Eliminar</span>
                        @endif
                    </button>
                @endif
                @if($removable)
                    <li>
                        <button type="button"
                            x-on:click.stop.prevent="
                                Swal.fire({
                                    title: 'Eliminar archivo',
                                    text: '¿Seguro que quieres eliminar este archivo?',
                                    icon: 'warning',
                                    showCancelButton: true,
                                    customClass: {
                                        confirmButton: 'px-4 py-2 rounded-lg border-2 border-red-500 bg-red-100 text-red-500 transition-colors duration-300 hover:bg-red-200 hover:border-red-600 hover:text-red-600',
                                        cancelButton: 'hover:underline',
                                        actions: 'space-x-6',
                                    },
                                    buttonsStyling: false,
                                    confirmButtonText: 'Eliminar',
                                    cancelButtonText: 'Cancelar',
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        $wire.call('deleteMedia', {{ $media->id }})
                                    }
                                })
                            "
                            action="delete"
                            class="group flex items-center space-x-2 px-4 py-2 w-full transition-colors duration-300 hover:bg-black hover:text-white"
                        >
                            <x-lux::tabler-icons.trash class="w-4 h-4 transition-transform duration-300 group-hover:scale-125" />
                            <span>Eliminar</span>
                        </button>
                    </li>
                @endif
            </ul>
        </div>
    </div>
    @endif
</div>
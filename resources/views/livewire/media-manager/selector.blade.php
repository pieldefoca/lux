<div>
    <x-lux::modal>
        <x-lux::modal.panel size="lg">
            <x-lux::modal.title>{{ trans('lux::lux.select-media') }}</x-lux::modal.title>

            <input type="text" wire:model="selected" style="display: none;" />

            <div class="flex items-center justify-between pb-4 mb-4 border-b border-stone-200">
                <div class="flex items-center">
                    <div 
                        x-data="{ view: @entangle('view') }"
                        class="flex items-center border border-stone-300 rounded-md p-0.5"
                    >
                        <button wire:click="setView('list')" type="button" :class="{'bg-black rounded text-white': view === 'list'}" class="px-2 py-1">
                            <x-lux::tabler-icons.list class="w-5 h-5" />
                        </button>
                        <button wire:click="setView('grid')" type="button" :class="{'bg-black rounded text-white': view === 'grid'}" class="px-2 py-1">
                            <x-lux::tabler-icons.layout-grid class="w-5 h-5" />
                        </button>
                    </div>

                    <div class="h-6 w-px bg-stone-300 mx-4"></div>
                    
                    <div class="space-x-1">
                        <button 
                            wire:click="$set('type', null)" 
                            type="button" 
                            @class([
                                'px-2 py-px rounded text-sm text-stone-500', 
                                'bg-black text-white' => is_null($type),
                                'hover:bg-stone-200 hover:text-stone-600' => !is_null($type),
                            ])
                        >
                            Todo
                        </button>
                        <button 
                            wire:click="$set('type', '{{ Pieldefoca\Lux\Enum\MediaType::Image->value }}')" 
                            type="button" 
                            @class([
                                'px-2 py-px rounded text-sm text-stone-500', 
                                'bg-black text-white' => $type === Pieldefoca\Lux\Enum\MediaType::Image->value,
                                'hover:bg-stone-200 hover:text-stone-600' => $type !== Pieldefoca\Lux\Enum\MediaType::Image->value
                            ])
                        >
                            Imágenes
                        </button>
                        <button 
                            wire:click="$set('type', '{{ Pieldefoca\Lux\Enum\MediaType::Video->value }}')" 
                            type="button" 
                            @class([
                                'px-2 py-px rounded text-sm text-stone-500', 
                                'bg-black text-white' => $type === Pieldefoca\Lux\Enum\MediaType::Video->value,
                                'hover:bg-stone-200 hover:text-stone-600' => $type !== Pieldefoca\Lux\Enum\MediaType::Video->value
                            ])
                        >
                            Vídeos
                        </button>
                        <button 
                            wire:click="$set('type', '{{ Pieldefoca\Lux\Enum\MediaType::File->value }}')" 
                            type="button" 
                            @class([
                                'px-2 py-px rounded text-sm text-stone-500', 
                                'bg-black text-white' => $type === Pieldefoca\Lux\Enum\MediaType::File->value,
                                'hover:bg-stone-200 hover:text-stone-600' => $type !== Pieldefoca\Lux\Enum\MediaType::File->value
                            ])
                        >
                            Archivos
                        </button>
                    </div>
                </div>

                <div class="flex items-center space-x-4">
                    <div>
                        <x-lux::link x-on:click="$refs.input.click()" icon="upload">
                            <span wire:loading.delay.remove>Añadir archivos</span>
                            <span wire:loading.delay>Cargando...</span>
                        </x-lux::link>
                        <input x-ref="input" wire:model.live="uploads" type="file" multiple style="display: none;" />
                    </div>

                    <x-lux::input.search wire:model.live="search" />
                </div>
            </div>

            <div wire:loading.delay.class="opacity-50 pointer-events-none">
                @if($this->mediaItems->isNotEmpty())
                    @if($view === 'list')
                        <table class="w-full table-fixed">
                            <thead class="rounded">
                                <tr class="bg-stone-200 text-xs">
                                    <th class="w-24 py-3 rounded-l text-left"></th>
                                    <th wire:click.live="orderBy('name')" class="py-3 text-left">Nombre</th>
                                    <th wire:click.live="orderBy('name')" class="w-32 py-3 text-left">Tamaño</th>
                                    <th wire:click.live="orderBy('type')" class="w-32 py-3 rounded-r text-left">Tipo</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($this->mediaItems as $media)
                                    @php
                                        $isSelected = $this->isSelected($media->id)
                                    @endphp
                                    <tr 
                                        wire:click="select({{$media->id}})" 
                                        wire:key="{{ uniqid() }}" 
                                        @class([
                                            'rounded-md last-of-type:border-none cursor-pointer transition-colors duration-300',
                                            'bg-teal-500/30' => $isSelected,
                                            'even:bg-stone-100 hover:bg-teal-500/10' => ! $isSelected,
                                        ])
                                    >
                                        <td class="py-1 rounded-l">
                                            <div class="flex justify-center">
                                                <div class="relative flex">
                                                    <x-lux::media-preview :$media without-type-icon class="!w-12" />
                                                    <div @class(['absolute inset-0 w-full h-full rounded-lg bg-teal-500/60', 'opacity-0' => !$isSelected])></div>
                                                    <x-lux::tabler-icons.check @class(['absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 text-white', 'opacity-0' => !$isSelected]) />
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-1">{{ $media->filename }}</td>
                                        <td class="py-1">{{ filesize_for_humans($media->size) }}</td>
                                        <td class="py-1 rounded-r">
                                            <x-lux::media-manager.media-type-pill :type="$media->media_type" />
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="flex flex-wrap gap-2">
                            @foreach($this->mediaItems as $media)
                                @php
                                    $isSelected = $this->isSelected($media->id)
                                @endphp
                                <button 
                                    wire:click="select({{$media->id}})" 
                                    wire:key="{{ uniqid() }}"
                                    type="button"
                                    @class([
                                        'relative rounded-md border ring-2 bg-gray-100 overflow-hidden',
                                        'border-stone-200 ring-transparent' => !$isSelected,
                                        'border-transparent ring-teal-500' => $isSelected,
                                    ])
                                >
                                    <div @class(['absolute inset-0 w-full h-full rounded-md bg-teal-500/30', 'opacity-0' => !$isSelected])></div>
                                    <x-lux::tabler-icons.check @class(['absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 text-white', 'opacity-0' => !$isSelected]) />
                                    @if($media->isImage())
                                        <img src="{{ $media->getThumbUrl() }}" class="w-16 aspect-square object-cover" />
                                    @elseif($media->isVideo())
                                        <video src="{{ $media->getUrl() }}" muted autoplay loop class="w-24 aspect-square object-cover" />
                                    @elseif($media->isPdf())
                                        <div class="flex flex-col items-center justify-center space-y-2 w-24 bg-stone-100 aspect-square rounded">
                                            <x-lux::tabler-icons.file-type-pdf class="w-12 h-12 text-stone-400" />
                                            <p class="text-[9px]">{{ $media->filename }}</p>
                                        </div>
                                    @else
                                        <div class="flex flex-col items-center justify-center space-y-2 w-24 bg-stone-100 aspect-square rounded">
                                            <x-lux::tabler-icons.file-invoice class="w-12 h-12 text-stone-400" />
                                            <p class="text-[9px]">{{ $media->filename }}</p>
                                        </div>                        
                                    @endif
                                </button>
                            @endforeach
                        </div>
                    @endif
                @else
                    <div class="grid place-items-center space-y-6">
                        <x-lux::tabler-icons.photo-off class="w-32 h-32 opacity-10" />
                        <p class="text-stone-500">No hay resultados</p>
                    </div>
                @endif
            </div>

            @if($this->canLoadMore)
                <div class="flex justify-center mt-6">
                    <x-lux::button wire:click="loadMore">
                        <span wire:loading.delay wire:target="loadMore">
                            <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </span>
                        <span wire:loading.remove wire:target="loadMore">Cargar más</span>
                    </x-lux::button>
                </div>
            @endif

            <x-lux::modal.footer>
                <div class="flex items-center justify-end w-full">
                    <div class="flex items-center space-x-8">
                        <x-lux::link x-on:click="$wire.hide()">Cancelar</x-lux::link>
                        <x-lux::button wire:click="confirmSelection" icon="check" :disabled="count($selected) === 0" class="relative">
                            Seleccionar
                            @if($multiple && count($selected) > 0)
                                <span class="absolute top-1.5 right-1.5 flex items-center justify-center w-3 h-3 bg-white text-black rounded-full text-[9px]">{{ count($selected) }}</span>
                            @endif
                        </x-lux::button>
                    </div>
                </div>
            </x-lux::modal.footer>
        </x-lux::modal.panel>
    </x-lux::modal>
</div>
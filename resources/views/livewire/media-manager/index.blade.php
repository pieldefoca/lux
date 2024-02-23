<x-lux::admin-page>
    <x-lux::title-bar :title="trans('lux::lux.media-manager-title')" :subtitle="trans('lux::lux.media-manager-subtitle')" />

    <div class="flex-grow mt-8">
        <div class="grid grid-cols-4 gap-4">
            <x-lux::card @class(['p-4', 'col-span-3' => $this->onlyOneSelected, 'col-span-4' => !$this->onlyOneSelected])>
                <div class="flex items-center justify-between pb-4 mb-4 border-b border-stone-200">
                    <div class="flex items-center">
                        <div>
                            <div 
                                x-data="{
                                    view: @entangle('view'),
                                }"
                                class="flex items-center border border-stone-300 rounded-md p-0.5"
                            >
                                <button wire:click="setView('list')" type="button" :class="{'bg-black rounded text-white': view === 'list'}" class="px-2 py-1">
                                    <x-lux::tabler-icons.list class="w-5 h-5" />
                                </button>
                                <button wire:click="setView('grid')" type="button" :class="{'bg-black rounded text-white': view === 'grid'}" class="px-2 py-1">
                                    <x-lux::tabler-icons.layout-grid class="w-5 h-5" />
                                </button>
                            </div>
                        </div>
    
                        <div class="h-6 w-px bg-stone-300 mx-4"></div>
    
                        <div class="space-x-2">
                            <button 
                                wire:click="$set('type', null)" 
                                type="button" 
                                @class([
                                    'rounded px-2 py-1 text-sm text-stone-500 transition-colors duration-300', 
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
                                    'rounded px-2 py-1 text-sm text-stone-500 transition-colors duration-300', 
                                    'bg-black text-white' => $type === Pieldefoca\Lux\Enum\MediaType::Image->value,
                                    'hover:bg-stone-200 hover:text-stone-600' => $type !== Pieldefoca\Lux\Enum\MediaType::Image->value,
                                ])
                            >
                                Imágenes
                            </button>
                            <button 
                                wire:click="$set('type', '{{ Pieldefoca\Lux\Enum\MediaType::Video->value }}')" 
                                type="button" 
                                @class([
                                    'rounded px-2 py-1 text-sm text-stone-500 transition-colors duration-300', 
                                    'bg-black text-white' => $type === Pieldefoca\Lux\Enum\MediaType::Video->value,
                                    'hover:bg-stone-200 hover:text-stone-600' => $type !== Pieldefoca\Lux\Enum\MediaType::Video->value,
                                ])
                            >
                                Vídeos
                            </button>
                            <button 
                                wire:click="$set('type', '{{ Pieldefoca\Lux\Enum\MediaType::File->value }}')" 
                                type="button" 
                                @class([
                                    'rounded px-2 py-1 text-sm text-stone-500 transition-colors duration-300', 
                                    'bg-black text-white' => $type ===  Pieldefoca\Lux\Enum\MediaType::File->value,
                                    'hover:bg-stone-200 hover:text-stone-600' => $type !== Pieldefoca\Lux\Enum\MediaType::File->value,
                                ])
                            >
                                Archivos
                            </button>
                        </div>
                    </div>
    
                    <div class="flex items-center space-x-4">
                        <x-lux::input.search wire:model.live="search" />
    
                        <div x-data>
                            <x-lux::button x-on:click="$refs.input.click()" icon="upload">Subir ficheros</x-lux::button>
                            <input x-ref="input" wire:model="uploads" type="file" multiple style="display: none;"/>
                        </div>
                    </div>
                </div>
    
                <div class="relative">
                    <div wire:loading.delay>
                        <div class="absolute inset-0 grid place-items-center h-full bg-black/10 rounded z-10">
                            <span class="loader"></span>
                        </div>
                    </div>
    
                    <div wire:loading.delay.class="opacity-30">
                        @if($this->mediaItems->isEmpty())
                            <div class="grid place-items-center w-full py-12 space-y-6">
                                @if(is_null($type))
                                    <x-lux::tabler-icons.photo-video class="w-36 h-36 opacity-10" />
                                    <p class="text-stone-600">{{ trans('lux::lux.there-are-no-files') }}</p>
                                @elseif($type === Pieldefoca\Lux\Enum\MediaType::Image->value)
                                    <x-lux::tabler-icons.photo class="w-36 h-36 opacity-10" />
                                    <p class="text-stone-600">{{ trans('lux::lux.there-are-no-images') }}</p>
                                @elseif($type === Pieldefoca\Lux\Enum\MediaType::Video->value)
                                    <x-lux::tabler-icons.movie class="w-36 h-36 opacity-10" />
                                    <p class="text-stone-600">{{ trans('lux::lux.there-are-no-videos') }}</p>
                                @elseif($type === Pieldefoca\Lux\Enum\MediaType::File->value)
                                    <x-lux::tabler-icons.file-invoice class="w-36 h-36 opacity-10" />
                                    <p class="text-stone-600">{{ trans('lux::lux.there-are-no-files') }}</p>
                                @endif
                            </div>
                        @else
                            @if($view === 'list')
                                <table class="w-full">
                                    <thead class="rounded">
                                        <tr class="bg-stone-100 text-xs">
                                            {{-- <th class="px-2 py-3 text-left rounded-l">
                                                <input type="checkbox" />
                                            </th> --}}
                                            <th class="py-3 text-left rounded-l"></th>
                                            <th wire:click.live="orderBy('name')" class="py-3 text-left">Nombre</th>
                                            <th wire:click.live="orderBy('type')" class="py-3 text-left">Tipo</th>
                                            <th class="py-3 text-left">Tamaño</th>
                                            <th class="py-3 text-left rounded-r"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($this->mediaItems as $media)
                                            <tr class="border-b border-stone-200">
                                                {{-- <td class="px-2 py-2">
                                                    <input type="checkbox" />
                                                </td> --}}
                                                <td class="py-2">
                                                    <x-lux::media-preview :$media class="!w-16 transition-transform duration-300 hover:scale-150 hover:z-10" />
                                                </td>
                                                <td class="py-2">{{ $media->name }}</td>
                                                <td class="py-2">
                                                    <x-lux::media-manager.media-type-pill :type="$media->media_type" />
                                                </td>
                                                <td class="py-2">{{ filesize_for_humans($media->size) }}</td>
                                                <td class="py-2 text-center">
                                                    <div class="space-x-2">
                                                        <x-lux::button.icon x-on:click="$dispatch('edit-media', { media: {{ $media->id }} })" action="edit" />
                                                        <x-lux::button.icon wire:click="deleteMedia({{ $media->id }})" action="delete" />
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <div class="flex flex-wrap gap-4">
                                    @foreach($this->mediaItems as $media)
                                        <x-lux::media-preview :media="$media" editable removable class="!w-36" />
                                    @endforeach
                                </div>
                            @endif
    
                            <div class="flex items-center space-x-8 mt-8 w-full">
                                <div>
                                    <x-lux::input.select native wire:model.live="perPage" class="!w-24">
                                        <option value="10">10</option>
                                        <option value="25">25</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                    </x-lux::input.select>
                                </div>
                                <div class="flex-grow">
                                    {{ $this->mediaItems->links('lux::pagination') }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </x-lux::card>
        </div>
    </div>

    <livewire:media-manager.slideover/>
</x-lux::admin-page>
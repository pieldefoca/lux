<x-lux::modal title="Seleccionar">
    <div>
        <input type="text" wire:model="selected" style="display: none;" />

        <div class="flex items-center justify-between pb-4 mb-4 border-b border-stone-200">
            <div class="space-x-4">
                <button 
                    wire:click="$set('type', null)" 
                    type="button" 
                    @class(['text-sm text-stone-500', 'rounded px-2 py-px bg-black text-white' => is_null($type)])
                >
                    Todo
                </button>
                <button 
                    wire:click="$set('type', '{{ Pieldefoca\Lux\Enum\MediaType::Image->value }}')" 
                    type="button" 
                    @class(['text-sm text-stone-500', 'rounded px-2 py-px bg-black text-white' => $type === Pieldefoca\Lux\Enum\MediaType::Image->value])
                >
                    Imágenes
                </button>
                <button 
                    wire:click="$set('type', '{{ Pieldefoca\Lux\Enum\MediaType::Video->value }}')" 
                    type="button" 
                    @class(['text-sm text-stone-500', 'rounded px-2 py-px bg-black text-white' => $type === Pieldefoca\Lux\Enum\MediaType::Video->value])
                >
                    Vídeos
                </button>
                <button 
                    wire:click="$set('type', '{{ Pieldefoca\Lux\Enum\MediaType::File->value }}')" 
                    type="button" 
                    @class(['text-sm text-stone-500', 'rounded px-2 py-px bg-black text-white' => $type === Pieldefoca\Lux\Enum\MediaType::File->value])
                >
                    Archivos
                </button>
            </div>

            <div>
                <x-lux::input.search wire:model.live="search" />
            </div>
        </div>

        @if($this->mediaItems->isNotEmpty())
            <div class="flex flex-wrap gap-4">
                @foreach($this->mediaItems as $media)
                    @php
                        $isSelected = $this->isSelected($media->id)
                    @endphp
                    <button 
                        wire:click="select({{$media->id}})" 
                        type="button"
                        @class([
                            'relative rounded-md border ring-2 bg-gray-100 overflow-hidden',
                            'border-stone-200 ring-transparent' => !$isSelected,
                            'border-transparent ring-teal-500' => $isSelected,
                        ])
                    >
                        <div @class(['absolute inset-0 w-full h-full rounded-md bg-teal-500/30 backdrop-blur-[1px]', 'opacity-0' => !$isSelected])></div>
                        <x-lux::tabler-icons.check @class(['absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 text-white', 'opacity-0' => !$isSelected]) />
                        @if($media->isImage())
                            <div class="absolute top-0.5 left-0.5 p-0.5 rounded bg-black/60 text-white backdrop-blur-[1px]">
                                <x-lux::tabler-icons.photo class="w-3 h-3" />
                            </div>
                            <img src="{{ $media->getUrl() }}" class="w-24 aspect-square object-cover" />
                        @elseif($media->isVideo())
                            <div class="absolute top-0.5 left-0.5 p-0.5 rounded bg-black/60 text-white backdrop-blur-[1px]">
                                <x-lux::tabler-icons.movie class="w-3 h-3" />
                            </div>
                            <video src="{{ $media->getUrl() }}" muted autoplay loop class="w-24 aspect-square object-cover" />
                        @elseif($media->isPdf())
                            <div class="absolute top-0.5 left-0.5 p-0.5 rounded bg-black/60 text-white backdrop-blur-[1px]">
                                <x-lux::tabler-icons.file-type-pdf class="w-3 h-3" />
                            </div>
                            <div class="flex flex-col items-center justify-center space-y-2 w-24 bg-stone-100 aspect-square rounded">
                                <x-lux::tabler-icons.file-type-pdf class="w-12 h-12 text-stone-400" />
                                <p class="text-[9px]">{{ $media->filename }}</p>
                            </div>
                        @else
                            <div class="absolute top-0.5 left-0.5 p-0.5 rounded bg-black/60 text-white backdrop-blur-[1px]">
                                <x-lux::tabler-icons.file-invoice class="w-3 h-3" />
                            </div>
                            <div class="flex flex-col items-center justify-center space-y-2 w-24 bg-stone-100 aspect-square rounded">
                                <x-lux::tabler-icons.file-invoice class="w-12 h-12 text-stone-400" />
                                <p class="text-[9px]">{{ $media->filename }}</p>
                            </div>                        
                        @endif
                    </button>
                @endforeach
            </div>
        @else
            <div class="grid place-items-center space-y-6">
                <x-lux::tabler-icons.photo-off class="w-32 h-32 opacity-10" />
                <p class="text-stone-500">No hay resultados</p>
            </div>
        @endif
    </div>

    <x-slot name="footer">
        <div class="flex items-center justify-between w-full">
            <div x-data>
                <x-lux::link x-on:click="$refs.input.click()" icon="upload">Subir</x-lux::link>
                <input x-ref="input" wire:model.live="uploads" type="file" multiple style="display: none;" />
            </div>

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
    </x-slot>
</x-lux::modal>
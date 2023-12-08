<div>
    <x-slot name="title">{{ trans('lux::lux.media-manager-title') }}</x-slot>
    <x-slot name="subtitle">{{ trans('lux::lux.media-manager-subtitle') }}</x-slot>

    <div class="grid grid-cols-4 gap-4">
        <x-lux::card @class(['p-4', 'col-span-3' => $this->onlyOneSelected, 'col-span-4' => !$this->onlyOneSelected])>
            <div class="flex items-center justify-between pb-4 mb-4 border-b border-stone-200">
                <div class="space-x-4">
                    <button wire:click="$set('type', null)" type="button" @class(['text-sm text-stone-500', 'rounded px-2 py-px bg-black text-white' => is_null($type)])>Todo</button>
                    <button wire:click="$set('type', '{{ Pieldefoca\Lux\Enum\MediaType::Image->value }}')" type="button" @class(['text-sm text-stone-500', 'rounded px-2 py-px bg-black text-white' => $type === Pieldefoca\Lux\Enum\MediaType::Image->value])>Imágenes</button>
                    <button wire:click="$set('type', '{{ Pieldefoca\Lux\Enum\MediaType::Video->value }}')" type="button" @class(['text-sm text-stone-500', 'rounded px-2 py-px bg-black text-white' => $type === Pieldefoca\Lux\Enum\MediaType::Video->value])>Vídeos</button>
                    <button wire:click="$set('type', '{{ Pieldefoca\Lux\Enum\MediaType::File->value }}')" type="button" @class(['text-sm text-stone-500', 'rounded px-2 py-px bg-black text-white' => $type ===  Pieldefoca\Lux\Enum\MediaType::File->value])>Archivos</button>
                </div>

                <div class="flex items-center space-x-4">
                    <x-lux::input.search wire:model.live="search" />

                    <div x-data>
                        <x-lux::button x-on:click="$refs.input.click()" icon="upload">Subir ficheros</x-lux::button>
                        <input x-ref="input" wire:model="uploads" type="file" multiple style="display: none;"/>
                    </div>
                </div>
            </div>

            <div class="flex flex-wrap gap-4">
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
                    @foreach($this->mediaItems as $media)
                        <x-lux::media-preview 
                            :media="$media"
                            editable
                            removable
                        />
                    @endforeach
                @endif
            </div>
        </x-lux::card>
    </div>

    <livewire:media-manager.slideover/>
</div>
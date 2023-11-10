<div>
    <x-slot name="title">{{ trans('lux::lux.media-manager-title') }}</x-slot>
    <x-slot name="subtitle">{{ trans('lux::lux.media-manager-subtitle') }}</x-slot>

    <div class="grid grid-cols-4 gap-4">
        <x-lux::card @class(['p-4', 'col-span-3' => $this->onlyOneSelected, 'col-span-4' => !$this->onlyOneSelected])>
            <div class="flex items-center justify-between pb-4 mb-4 border-b border-stone-200">
                <div class="space-x-4">
                    <button wire:click="$set('filters.type', 'all')" type="button" @class(['text-sm text-stone-500', 'rounded px-2 py-px bg-black text-white' => $filters['type'] === 'all'])>Todo</button>
                    <button wire:click="$set('filters.type', 'images')" type="button" @class(['text-sm text-stone-500', 'rounded px-2 py-px bg-black text-white' => $filters['type'] === 'images'])>Imágenes</button>
                    <button wire:click="$set('filters.type', 'videos')" type="button" @class(['text-sm text-stone-500', 'rounded px-2 py-px bg-black text-white' => $filters['type'] === 'videos'])>Vídeos</button>
                    <button wire:click="$set('filters.type', 'files')" type="button" @class(['text-sm text-stone-500', 'rounded px-2 py-px bg-black text-white' => $filters['type'] === 'files'])>Archivos</button>
                </div>

                <div x-data>
                    <x-lux::button x-on:click="$refs.input.click()" icon="upload">Subir ficheros</x-lux::button>
                    <input x-ref="input" wire:model="uploads" type="file" multiple style="display: none;"/>
                </div>
            </div>

            <div>
                @foreach(Pieldefoca\Lux\Models\Media::all() as $media)
                    <img wire:click="select({{ $media->id }})" src="{{ asset('uploads/' . $media->filename) }}" class="w-36 aspect-square object-cover rounded-md"/>
                @endforeach
            </div>
        </x-lux::card>

        @if($this->onlyOneSelected)
            <livewire:media-manager.info-sidebar/>
        @endif

    </div>
</div>
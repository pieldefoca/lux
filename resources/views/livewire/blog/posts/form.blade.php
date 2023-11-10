<form class="space-y-6">
    <x-lux::locale-selector />

    <x-lux::input.inline-group translatable required label="Título" :error="$errors->first('title.*')">
        <x-lux::input.text wire:model.live="title" />
    </x-lux::input.inline-group>

    <x-lux::input.inline-group translatable required label="URL" :error="$errors->first('slug.*')">
        <x-lux::input.slug wire:model="slug" />
    </x-lux::input.inline-group>

    <x-lux::input.inline-group translatable required label="Imagen principal" :error="$errors->first('featuredImage')">
        <x-lux::input.media wire:model="featuredImage" type="image" />
    </x-lux::input.inline-group>

    <x-lux::input.inline-group required label="Categoría">
        <x-lux::input.select
            multiple
            wire:model="categories"
            options="categoryOptions"
        />
    </x-lux::input.inline-group>

    <x-lux::input.inline-group label="Galería">
        <div class="flex flex-col">
            <input wire:model="gallery" type="file" multiple />
            <div>
                @foreach($gallery as $media)
                    {{-- <img src="{{ $media->temporaryUrl() }}" class="w-12 h-12" /> --}}
                @endforeach
            </div>
        </div>
    </x-lux::input.inline-group>

    <x-lux::input.inline-group translatable required label="Contenido" :error="$errors->first('body.*')">
        <x-lux::input.rich-text wire:model="body" />
    </x-lux::input.inline-group>
</form>

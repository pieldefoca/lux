<div>
    <x-slot name="title">{{ trans('lux::lux.traducciones-titulo') }}</x-slot>
    <x-slot name="subtitle">{{ trans('lux::lux.traducciones-subtitulo') }}</x-slot>

    <button type="button" wire:click="save">Guardar</button>

    <div class="grid grid-cols-2 gap-8">
        <div>
            {{ $defaultLocaleCode }}
            <x-lux::input.select native wire:model.live="selectedFile">
                @foreach($langFiles as $humanName => $filename)
                    <option value="{{ $filename }}">{{ $humanName }}</option>
                @endforeach
            </x-lux::input.select>

            <div>
                @foreach($this->translations as $key => $translation)
                    <div>
                        <x-lux::input.text wire:model="translations.{{$key}}" />
                    </div>
                @endforeach
            </div>
        </div>

        <div>
            {{ $this->editingLocaleCode }}
        </div>
    </div>
</div>
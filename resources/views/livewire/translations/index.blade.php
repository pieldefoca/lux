<div>
    <script src="https://unpkg.com/@popperjs/core@2"></script>
    <script src="https://unpkg.com/tippy.js@6"></script>

    <x-slot name="title">Traducciones</x-slot>
    <x-slot name="subtitle">Aquí puedes traducir los textos de la página</x-slot>

    <x-slot name="actions">
        <x-lux::button x-on:click="$dispatch('save-translations')" icon="device-floppy">Guardar</x-lux::button>
    </x-slot>

    <div class="space-y-4">
        <div class="flex items-center gap-4">
            <x-lux::input.group label="Fichero de traducciones">
                <x-lux::input.select native wire:model.live="selectedFile">
                    @foreach($langFiles as $humanName => $filename)
                        <option value="{{ $filename }}">{{ $filename }}</option>
                    @endforeach
                </x-lux::input.select>
            </x-lux::input.group>

            <x-lux::input.group label="Buscar">
                <x-lux::input.search wire:model.live="search" />
            </x-lux::input.group>
        </div>

        <div class="grid grid-cols-2 gap-8">
            <div class="space-y-3">
                <div class="inline-flex flex-col space-y-1 px-2 py-1 border border-stone-300 bg-stone-200 rounded-md">
                    <div class="flex items-center space-x-1">
                        <img src="{{ $this->defaultLocale->flagUrl }}" class="w-4 h-4 rounded-full" />
                        <p class="text-xs">{{ $this->defaultLocale->code }}</p>
                    </div>
                    <span class="text-[9px] text-stone-400">Idioma por defecto</span>
                </div>

                <div class="space-y-3">
                    @foreach($this->filteredDefaultTranslations as $key => $translation)
                        <div wire:key="{{ $key }}" class="flex flex-col items-end">
                            <div x-data class="flex items-center space-x-1">
                                <p x-ref="fileKey" class="text-[9px] text-stone-500">{{ $this->selectedFileKey }}.{{ $key }}</p>
                                <button
                                    @click="
                                        $tooltip('Copiado')
                                        navigator.clipboard.writeText($refs.fileKey.innerText)
                                    "
                                    type="button"
                                >
                                    <x-lux::tabler-icons.copy class="w-3 h-3 cursor-pointer text-stone-500 transition-all duration-300 hover:text-stone-800 hover:scale-125" />
                                </button>
                            </div>
                            <x-lux::input.translation wire:model="defaultTranslations.{{$key}}" />
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="space-y-3">
                <div 
                    x-data="{
                        open: false,
                    }"
                    @click.outside="open = false"
                    class="relative inline-flex items-center px-2 py-1 border border-stone-300 bg-stone-200 rounded-md"
                >
                    <button @click="open = !open" type="button" class="flex flex-col space-y-1 min-w-[5rem]">
                        <div class="flex items-center justify-between w-full space-x-2">
                            <div class="flex items-center space-x-1">
                                <img src="{{ $this->editingLocale->flagUrl }}" class="w-4 h-4 rounded-full" />
                                <p class="text-xs">{{ $this->editingLocale->code }}</p>
                            </div>
                            <x-lux::tabler-icons.chevron-down class="w-3 h-3 text-stone-500" />
                        </div>
                        <span class="text-[9px] text-stone-400">Traducir a</span>
                    </button>

                    <div x-show="open" x-transition class="absolute top-full left-0 w-20 flex flex-col mt-1 p-1.5 bg-white rounded-md shadow">
                        @foreach($locales as $locale)
                            <button wire:click="selectLocale('{{$locale}}')" @click="open = false" type="button" class="flex items-center space-x-2 px-2 py-1 rounded transition-colors duration-300 hover:bg-black hover:text-white">
                                <img src="{{ asset("vendor/lux/img/flags/{$locale}.svg") }}" class="w-3 h-3" />
                                <span class="text-xs">{{ $locale }}</span>
                            </button>
                        @endforeach
                    </div>
                </div>            

                <div class="space-y-3">
                    @foreach($this->filteredEditingTranslations as $key => $translation)
                        <div wire:key="{{ $key }}" class="flex flex-col items-end">
                            <div x-data class="flex items-center space-x-1">
                                <p x-ref="fileKey" class="text-[9px] text-stone-500">{{ $this->selectedFileKey }}.{{ $key }}</p>
                                <button
                                    @click="
                                        $tooltip('Copiado')
                                        navigator.clipboard.writeText($refs.fileKey.innerText)
                                    "
                                    type="button"
                                >
                                    <x-lux::tabler-icons.copy class="w-3 h-3 cursor-pointer text-stone-500 transition-all duration-300 hover:text-stone-800 hover:scale-125" />
                                </button>
                            </div>
                            <x-lux::input.translation wire:model="editingTranslations.{{$key}}" />
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
<div>
    <script defer src="https://unpkg.com/@alpinejs/ui@3.13.1-beta.0/dist/cdn.min.js"></script>

    <x-slot name="title">Editar página</x-slot>
    <x-slot name="subtitle">Estás editando la página "{{ $page->name }}"</x-slot>

    <x-slot name="actions">
        <div class="flex items-center space-x-8">
            <x-lux::link link="" target="_blank">Ver página</x-lux::link>
            <x-lux::button x-on:click="$dispatch('save-page')" icon="device-floppy">Guardar</x-lux::button>
        </div>
    </x-slot>

    <div x-data="{activeTab: 0}" x-tabs x-model="activeTab" class="max-w-6xl mx-auto">
        <x-lux::locale-selector />

        <div x-tabs:list class="flex items-center justify-center gap-4 mt-6">
            <button
                type="button"
                x-tabs:tab
                :class="{
                'bg-stone-800 border-stone-400 text-white': activeTab === 0,
                'border-transparent text-stone-500 hover:bg-stone-200': activeTab !== 0,
            }"
                class="px-6 py-2 border border-stone-300 rounded-full transition-all duration-300"
            >Datos de la página</button>
            <button
                type="button"
                x-tabs:tab
                :class="{
                'bg-stone-800 border-stone-400 text-white': activeTab === 1,
                'border-transparent text-stone-500 hover:bg-stone-200': activeTab !== 1,
            }"
                class="px-6 py-2 border border-stone-300 rounded-full transition-all duration-300"
            >Contenido</button>
        </div>

        <div x-tabs:panels class="border border-stone-200 bg-white rounded-lg shadow p-6 mt-3">
            <div x-tabs:panel class="space-y-6">
                <form class="space-y-6">
                    <x-lux::input.inline-group required label="Nombre" :error="$errors->first('name')">
                        <x-lux::input.text wire:model="name" />
                    </x-lux::input.inline-group>

                    <x-lux::input.inline-group required translatable label="URL" :error="$errors->first('slug')">
                        <div class="w-full space-y-1">
                            <x-lux::input.slug wire:model.blur="slug" :prefix="config('app.url').'/'.$currentLocaleCode.'/'" />
                            @if($page->isDynamic())
                                <span class="flex items-center space-x-2 text-xs font-bold text-amber-500">
                                    <x-lux::tabler-icons.alert-triangle class="w-4 h-4" />
                                    <span>No modifiques la parte que está entre corchetes o la página dejará de funcionar</span>
                                </span>
                            @endif
                        </div>
                    </x-lux::input.inline-group>

                    @role('superadmin')
                        <x-lux::input.inline-group danger required label="Destino">
                            <x-lux::input.select native wire:model="target">
                                <option value="controller">Controlador</option>
                                <option value="livewire">Livewire</option>
                            </x-lux::input.select>
                        </x-lux::input.inline-group>

                        <div x-show="$wire.target === 'controller'">
                            <x-lux::input.inline-group danger required label="Controlador" :error="$errors->first('controller')">
                                <x-lux::input.text wire:model="controller" />
                            </x-lux::input.inline-group>

                            <x-lux::input.inline-group danger required label="Acción" :error="$errors->first('controller_action')">
                                <x-lux::input.text wire:model="controller_action" />
                            </x-lux::input.inline-group>
                        </div>

                        <div x-show="$wire.target === 'livewire'">
                            <x-lux::input.inline-group danger required label="Componente" :error="$errors->first('livewire_component')">
                                <x-lux::input.text wire:model="livewire_component" />
                            </x-lux::input.inline-group>
                        </div>
                    @endrole

                    @if(!$page->isDynamic())
                        <x-lux::input.inline-group translatable label="Título (SEO)" :error="$errors->first('title')">
                            <x-lux::input.text translatable wire:model="title" />
                        </x-lux::input.inline-group>

                        <x-lux::input.inline-group translatable label="Descripción (SEO)" :error="$errors->first('description')">
                            <x-lux::input.textarea translatable wire:model="description" />
                        </x-lux::input.inline-group>

                        <x-lux::input.inline-group label="Visible" help="¿Quieres publicar esta página?" :error="$errors->first('visible')">
                            <x-lux::input.toggle wire:model="visible" />
                        </x-lux::input.inline-group>
                    @endif
                </form>
            </div>

            <div x-tabs:panel>
                <div class="mb-3">
                    <div class="inline-flex items-center space-x-1 px-2 py-1 border border-amber-200 rounded bg-amber-50">
                        <span class="text-xs">Estás editando:</span>
                        <div class="flex items-center space-x-1">
                            <img src="{{ asset('vendor/lux/img/flags/'.$currentLocaleCode.'.svg') }}" class="w-3 h-3" />
                            <p class="text-xs">{{ $currentLocaleCode }}</p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2 items-start gap-8">
                    <x-lux::card title="Textos" class="p-4">
                        <x-slot name="actions">
                            <x-lux::input.group label="">
                                <x-lux::input.search wire:model.live="search" />
                            </x-lux::input.group>
                        </x-slot>

                        <div class="pt-2 space-y-4">
                            @foreach($this->filteredTranslations as $locale => $localeTranslations)
                                <div class="space-y-4" wire:key="{{ uniqid() }}">
                                    @foreach($localeTranslations as $index => $translation)
                                        @if($page->isLegalPage() && $index === 'content') @continue @endif

                                        <x-lux::input.translation
                                            wire:model="translations.{{$locale}}.{{$index}}"
                                            @class(['hidden' => $this->currentLocaleCode !== $locale])
                                            wire:key="{{ uniqid() }}"
                                        />
                                    @endforeach
                                </div>
                            @endforeach

                            @if($page->isLegalPage())
                                <x-lux::input.group translatable label="Contenido">
                                    <x-lux::input.rich-text wire:model="legalPageContent" />
                                </x-lux::input.group>
                            @endif
                        </div>
                    </x-lux::card>

                    <x-lux::card title="Imágenes / Vídeos / Documentos" class="p-4">
                        <div class="pt-2 space-y-2">
                            <p class="flex items-center space-x-2 uppercase font-bold">
                                <x-lux::tabler-icons.photo />
                                <span>{{ trans('lux::lux.images') }}</span>
                            </p>

                            @if($this->images->isNotEmpty())
                                <div class="flex items-center flex-wrap gap-4">
                                    @foreach($this->images as $image)
                                        <div class="flex flex-col space-y-1">
                                            <x-lux::media-preview
                                                :media="$image"
                                                :key="$image->pivot->key"
                                                editable
                                                swappable
                                            />
                                            <span class="text-[9px] text-stone-500">{{ $image->pivot->key }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="grid place-items-center py-8 space-y-6">
                                    <x-lux::tabler-icons.photo-off class="w-16 h-16 opacity-10" />
                                    <p class="text-stone-500">No hay imágenes editables</p>
                                </div>
                            @endif
                        </div>

                        <hr class="mt-8 mb-6">

                        <div class="space-y-2">
                            <p class="flex items-center space-x-2 uppercase font-bold">
                                <x-lux::tabler-icons.movie />
                                <span>{{ trans('lux::lux.videos') }}</span>
                            </p>

                            @if($this->videos->isNotEmpty())
                                <div class="flex items-center flex-wrap gap-4">
                                    @foreach($this->videos as $video)
                                        <div class="flex flex-col space-y-1">
                                            <x-lux::media-preview
                                                :media="$video"
                                                :key="$video->pivot->key"
                                                editable
                                                swappable
                                            />
                                            <span class="text-[9px] text-stone-500">{{ $video->pivot->key }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="grid place-items-center py-4 space-y-4">
                                    <x-lux::tabler-icons.movie-off class="w-16 h-16 opacity-10" />
                                    <p class="text-stone-500">No hay vídeos editables</p>
                                </div>
                            @endif
                        </div>

                        <hr class="mt-8 mb-6">

                        <div class="space-y-2">
                            <p class="flex items-center space-x-2 uppercase font-bold">
                                <x-lux::tabler-icons.file-invoice />
                                <span>{{ trans('lux::lux.files') }}</span>
                            </p>

                            @if($this->files->isNotEmpty())
                                <div class="flex items-center flex-wrap gap-4">
                                    @foreach($this->files as $file)
                                        <div class="flex flex-col space-y-1">
                                            <x-lux::media-preview
                                                :media="$file"
                                                :key="$file->pivot->key"
                                                editable
                                                swappable
                                            />
                                            <span class="text-[9px] text-stone-500">{{ $file->pivot->key }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="grid place-items-center py-8 space-y-6">
                                    <x-lux::tabler-icons.files-off class="w-16 h-16 opacity-10" />
                                    <p class="text-stone-500">No hay archivos editables</p>
                                </div>
                            @endif
                        </div>

                        <hr class="mt-8 mb-6">

                        <div class="space-y-2">
                            <p class="flex items-center space-x-2 uppercase font-bold">
                                <x-lux::tabler-icons.photo-video />
                                <span>{{ trans('lux::lux.common-elements-multiple-pages') }}</span>
                            </p>

                            @if($this->commonMedia->isNotEmpty())
                                <div class="flex items-center flex-wrap gap-4">
                                    @foreach($this->commonMedia as $file)
                                        <div class="flex flex-col space-y-1">
                                            <x-lux::media-preview
                                                :media="$file->media"
                                                :key="$file->key"
                                                editable
                                                swappable
                                            />
                                            <span class="text-[9px] text-stone-500">{{ $file->key }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="grid place-items-center py-8 space-y-6">
                                    <x-lux::tabler-icons.files-off class="w-16 h-16 opacity-10" />
                                    <p class="text-stone-500">No hay archivos editables</p>
                                </div>
                            @endif
                        </div>
                    </x-lux::card>
                </div>
            </div>
        </div>
    </div>
</div>

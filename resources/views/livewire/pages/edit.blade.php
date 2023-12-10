<div>
    <script defer src="https://unpkg.com/@alpinejs/ui@3.13.1-beta.0/dist/cdn.min.js"></script>

    <x-slot name="title">Editar página</x-slot>
    <x-slot name="subtitle">Estás editando la página "{{ $page->name }}"</x-slot>

    <x-slot name="actions">
        <div class="flex items-center space-x-8">
            <x-lux::link link="{{ page($page->key) }}" target="_blank">Ver página</x-lux::link>
            <x-lux::button x-on:click="$dispatch('save-page')" icon="device-floppy">Guardar</x-lux::button>
        </div>
    </x-slot>

    <x-lux::locale-selector />

    <div x-data="{activeTab: 0}" x-tabs x-model="activeTab">
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

                    @if(!$page->dynamic_page)
                        <x-lux::input.inline-group label="Página de inicio" help="¿Es esta la página de inicio?" :error="$errors->first('is_home_page')">
                            <div class="space-y-2">
                                <x-lux::input.toggle wire:model.live="is_home_page" />
                                @if(!empty($currentHomeMessage))
                                    <p class="flex items-start space-x-2 text-xs text-amber-500">
                                        <x-lux::tabler-icons.alert-triangle class="w-5 h-5 text-amber-500" />
                                        <span>{!! $currentHomeMessage !!}</span>
                                    </p>
                                @endif
                            </div>
                        </x-lux::input.inline-group>
                    @endif

                    @if($page->dynamic_page)
                        <x-lux::input.inline-group required translatable label="Prefijo de la URL" :error="$errors->first('slug_prefix')">
                            <x-lux::input.slug wire:model.blur="slug_prefix" :prefix="config('app.url').'/'.$currentLocaleCode.'/'" />
                        </x-lux::input.inline-group>
                    @else
                        <x-lux::input.inline-group required translatable label="URL" :error="$errors->first('slug')">
                            <x-lux::input.slug wire:model.blur="slug" :prefix="config('app.url').'/'.$currentLocaleCode.'/'" />
                        </x-lux::input.inline-group>
                    @endif

                    @role('superadmin')
                        <x-lux::input.inline-group danger required label="Vista">
                            <x-lux::input.select native wire:model="view">
                                @foreach($this->views as $view)
                                    <option value="{{ $view }}">{{ $view }}</option>
                                @endforeach
                            </x-lux::input.select>
                        </x-lux::input.inline-group>

                        <x-lux::input.inline-group danger required label="ID" :error="$errors->first('key')">
                            <x-lux::input.text wire:model="key" />
                        </x-lux::input.inline-group>
                    @endrole

                    @if(!$page->dynamic_page)
                        <x-lux::input.inline-group translatable label="Título (SEO)" :error="$errors->first('title')">
                            <x-lux::input.text wire:model="title" />
                        </x-lux::input.inline-group>

                        <x-lux::input.inline-group translatable label="Descripción (SEO)" :error="$errors->first('description')">
                            <x-lux::input.textarea wire:model="description" />
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
                        <div class="pt-2">
                            @foreach($translations as $locale => $localeTranslations)
                                <div class="space-y-4">
                                    @foreach($localeTranslations as $index => $translation)
                                        <x-lux::input.translation
                                            wire:model="translations.{{$locale}}.{{$index}}"
                                            @class(['hidden' => $this->currentLocaleCode !== $locale])
                                        />
                                    @endforeach
                                </div>
                            @endforeach
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

                <livewire:pages.page-media-form-modal />
            </div>
        </div>
    </div>

    <livewire:media-manager.selector />
</div>

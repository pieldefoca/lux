<x-lux::form class="min-h-screen flex flex-col">
    <script defer src="https://unpkg.com/@alpinejs/ui@3.13.1-beta.0/dist/cdn.min.js"></script>

    <x-lux::title-bar :title="trans('lux::pages.edit_title')" :subtitle="trans('lux::pages.edit_subtitle', ['name' => $page->name])" />

    <div x-data="{activeTab: 0}" x-tabs x-model="activeTab" class="flex-grow w-full 2xl:w-full 2xl:max-w-7xl 2xl:mx-auto mt-8">
        <x-lux::required-fields />

        <div x-tabs:list class="flex items-center justify-center mt-6">
            <button
                type="button"
                x-tabs:tab
                :class="{
                'bg-stone-800 border-stone-800 text-white': activeTab === 0,
                'border-stone-100 border-r-transparent bg-stone-100 text-stone-500 hover:bg-stone-200': activeTab !== 0,
            }"
                class="flex-grow px-6 py-2 border border-stone-300 rounded-l-lg transition-all duration-300"
            >Datos de la página</button>
            <button
                type="button"
                x-tabs:tab
                :class="{
                    'bg-stone-800 border-stone-800 text-white': activeTab === 1,
                    'border-stone-100 border-l-none bg-stone-100 text-stone-500 hover:bg-stone-200': activeTab !== 1,
                }"
                class="flex-grow px-6 py-2 border border-stone-300 rounded-r-lg transition-all duration-300"
            >Contenido</button>
        </div>

        <div x-tabs:panels class="border border-stone-200 bg-white rounded-lg shadow p-6 mt-3">
            <div x-tabs:panel class="space-y-6">
                <form class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <x-lux::input.group required label="Nombre" for="name" :error="$errors->first('name')">
                            <x-lux::input.text wire:model="name" id="name" />
                        </x-lux::input.group>

                        <x-lux::input.group required translatable label="URL" for="slug" :error="$errors->first('slug')">
                            <div class="w-full space-y-1">
                                <x-lux::input.slug wire:model="slug" :prefix="config('app.url').'/'.$locale.'/'" id="slug" />
                                @if($page->isDynamic())
                                    <span class="flex items-center space-x-2 text-xs font-bold text-amber-500">
                                        <x-lux::tabler-icons.alert-triangle class="w-4 h-4" />
                                        <span>No modifiques la partes que están entre llaves o la página dejará de funcionar</span>
                                    </span>
                                @endif
                            </div>
                        </x-lux::input.group>
                    </div>

                    @role('superadmin')
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <x-lux::input.group danger required label="Destino">
                                <x-lux::input.select wire:model="target">
                                    <option value="controller">Controlador</option>
                                    <option value="livewire">Livewire</option>
                                </x-lux::input.select>
                            </x-lux::input.group>

                            <div x-show="$wire.target === 'controller'" class="space-y-4">
                                <x-lux::input.group danger required label="Controlador" for="controller" :error="$errors->first('controller')">
                                    <x-lux::input.text wire:model="controller" id="controller" />
                                </x-lux::input.group>

                                <x-lux::input.group danger required label="Acción" for="controller_action" :error="$errors->first('controller_action')">
                                    <x-lux::input.text wire:model="controller_action" id="controller_action" />
                                </x-lux::input.group>
                            </div>

                            <div x-show="$wire.target === 'livewire'">
                                <x-lux::input.group danger required label="Componente" for="livewire_component" :error="$errors->first('livewire_component')">
                                    <x-lux::input.text wire:model="livewire_component" id="livewire_component" />
                                </x-lux::input.group>
                            </div>
                        </div>
                    @endrole

                    @if(!$page->isDynamic())
                        <x-lux::input.group translatable label="Título (SEO)" :error="$errors->first('seo_title')" help="Se recomienda entre 50 y 60 caracteres">
                            <x-lux::input.seo-title wire:model="seo_title" />
                            {{ $page->translate('seo_title', 'es') }}
                        </x-lux::input.group>

                        <x-lux::input.group translatable label="Descripción (SEO)" :error="$errors->first('seo_description')" help="Se recomienda entre 120 y 150 caracteres">
                            <x-lux::input.seo-description wire:model="seo_description" />
                        </x-lux::input.group>

                        <x-lux::input.group label="Visible" help="¿Quieres publicar esta página?" :error="$errors->first('visible')">
                            <x-lux::input.toggle wire:model="visible" />
                        </x-lux::input.group>
                    @endif
                </form>
            </div>

            <div x-tabs:panel>
                <div class="mb-3">
                    <div class="inline-flex items-center space-x-1 px-2 py-1 border border-amber-200 rounded bg-amber-50">
                        <span class="text-xs">{{ trans('lux::pages.you_are_editing') }}:</span>
                        <div class="flex items-center space-x-1">
                            <img src="{{ asset("vendor/lux/img/flags/{$locale}.svg") }}" class="w-3 h-3" />
                            <p class="text-xs">{{ $locale }}</p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2 items-start gap-8">
                    <x-lux::card title="Textos" class="p-4">
                        <x-slot name="actions">
                            <x-lux::input.group label="">
                                <x-lux::input.search wire:model.live="translationSearch" placeholder="{{ trans('lux::lux.search') }}..." />
                            </x-lux::input.group>
                        </x-slot>

                        <div class="pt-2 space-y-4">
                            @if(!$page->isLegalPage() && count($this->filteredTranslations) === 0)
                                <div class="flex flex-col items-center space-y-8">
                                    <x-lux::tabler-icons.search class="w-24 h-24 opacity-10" />

                                    <p class="text-gray-600">Parece que no existe ninguna traducción</p>
                                </div>
                            @else
                                @foreach($this->filteredTranslations as $index => $translations)
                                    <div class="space-y-4" wire:key="{{ uniqid() }}">
                                        @if($page->isLegalPage() && $index === 'content') @continue @endif

                                        <x-lux::input.tiptap toolbar="bold italic underline | bullet-list ordered-list" wire:model="translations.{{$index}}" />
                                    </div>
                                @endforeach
                            @endif

                            @if($page->isLegalPage())
                                <x-lux::input.group translatable label="Contenido">
                                    <x-lux::input.tiptap wire:model="legalPageContent" />
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

                            @if(!empty($this->images))
                                <div class="space-y-1.5">
                                    @foreach($this->images as $image)
                                        <div class="flex items-center justify-between gap-6 p-2 border border-gray-200 rounded-md">
                                            <div class="flex space-x-4">
                                                <img src="{{ $image['url'] }}" class="w-36 rounded-md aspect-video" />

                                                <div class="flex flex-col justify-between">
                                                    <div>
                                                        @if(Str::startsWith($image['key'], 'component.'))
                                                            <div x-tooltip="Si modificas esta imagen, los cambios se aplicarán en todas las páginas en las que se esté usando" class="flex items-center justify-center space-x-1 px-1 py-0.5 border border-yellow-300 bg-yellow-50 rounded-md text-xs text-yellow-700 cursor-help">
                                                                <x-lux::tabler-icons.exclamation-circle class="w-4 h-4" />
                                                                <p>Común a varias páginas</p>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="space-y-1">
                                                        <p class="text-sm">{{ $image['name'] }}</p>
                                                        <div class="flex items-center space-x-1 text-xs opacity-50">
                                                            <p>{{ $image['size'] }}</p>
                                                            @env('local')
                                                                <p> - {{ $image['key'] }}</p>
                                                            @endenv
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div>
                                                <x-lux::menu>
                                                    <x-lux::menu.button class="p-0.5 border-2 border-transparent rounded-lg opacity-40 transition-all duration-300 hover:opacity-100 hover:border-stone-800 hover:text-stone-800">
                                                        <x-lux::tabler-icons.dots-vertical />
                                                    </x-lux::menu.button>

                                                    <x-lux::menu.items>
                                                        <x-lux::menu.button-item
                                                            x-on:click="$wire.swapMedia({{ $image['id'] }}, '{{ $image['key'] }}'); open = false"
                                                        >
                                                            <x-lux::tabler-icons.click class="w-4 h-4" />
                                                            <span>{{ trans('lux::lux.change_image') }}</span>
                                                        </x-lux::menu.button-item>

                                                        <x-lux::menu.button-item x-on:click="$dispatch('edit-media', { media: {{ $image['id'] }} }); open = false">
                                                            <x-lux::tabler-icons.edit class="w-4 h-4" />
                                                            <span>{{ trans('lux::lux.edit_details') }}</span>
                                                        </x-lux::menu.button-item>
                                                    </x-lux::menu.items>
                                                </x-lux::menu>
                                            </div>
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
                    </x-lux::card>
                </div>
            </div>
        </div>
    </div>

    <x-lux::action-bar>
        <x-slot:leftSide>
            <a href="{{ route($page->id) }}" target="_blank">
                <x-lux::button.link>{{ trans('lux::pages.view_page') }}</x-lux::button.link>
            </a>
        </x-slot:leftSide>

        <div class="flex items-center space-x-8">
            <x-lux::button x-on:click="$dispatch('save-page')" icon="device-floppy">{{ trans('lux::lux.save') }}</x-lux::button>
            <a href="{{ route('lux.pages.index') }}">
                <x-lux::button.link>{{ trans('lux::lux.cancel') }}</x-lux::button.link>
            </a>
        </div>
    </x-lux::action-bar>
</x-lux::form>

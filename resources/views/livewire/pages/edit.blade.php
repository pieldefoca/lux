<div>
    <script defer src="https://unpkg.com/@alpinejs/ui@3.13.1-beta.0/dist/cdn.min.js"></script>

    <x-slot name="title">Editar página</x-slot>
    <x-slot name="subtitle">Estás editando la página "{{ $page->name }}"</x-slot>

    <x-slot name="actions">
        <x-lux::button x-on:click="$dispatch('save-page')" icon="device-floppy">Guardar</x-lux::button>
    </x-slot>

    <x-lux::locale-selector />

    <div x-data="{activeTab: 0}" x-tabs x-model="activeTab">
        <div x-tabs:list class="flex justify-between gap-2">
            <button
                type="button"
                x-tabs:tab
                :class="{
                'bg-black border-black text-white': activeTab === 0,
                'hover:bg-stone-100 hover:shadow': activeTab !== 0,
            }"
                class="w-full py-1 border border-stone-300 rounded-md text-xs transition-all duration-300"
            >Datos de la página</button>
            <button
                type="button"
                x-tabs:tab
                :class="{
                'bg-black border-black text-white': activeTab === 1,
                'hover:bg-stone-100 hover:shadow': activeTab !== 1,
            }"
                class="w-full py-1 border border-stone-300 rounded-md text-xs transition-all duration-300"
            >Contenido</button>
        </div>

        <div x-tabs:panels class="mt-6">
            <div x-tabs:panel class="space-y-6">
                <form class="space-y-6">
                    {{ $errors }}

                    <x-lux::input.inline-group required label="Nombre" :error="$errors->first('name')">
                        <x-lux::input.text wire:model="name" />
                    </x-lux::input.inline-group>

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

                    <x-lux::input.inline-group required translatable label="URL" :error="$errors->first('slug')">
                        <x-lux::input.slug wire:model.blur="slug" :prefix="config('app.url').'/'.$currentLocaleCode.'/'" />
                    </x-lux::input.inline-group>

                    <x-lux::input.inline-group required label="Vista">
                        <x-lux::input.select native wire:model="view">
                            @foreach($this->views as $view)
                                <option value="{{ $view }}">{{ $view }}</option>
                            @endforeach
                        </x-lux::input.select>
                    </x-lux::input.inline-group>

                    <x-lux::input.inline-group translatable label="Título (SEO)" :error="$errors->first('title')">
                        <x-lux::input.text wire:model="title" />
                    </x-lux::input.inline-group>

                    <x-lux::input.inline-group translatable label="Descripción (SEO)" :error="$errors->first('description')">
                        <x-lux::input.textarea wire:model="description" />
                    </x-lux::input.inline-group>

                    <x-lux::input.inline-group label="Visible" help="¿Quieres publicar esta página?" :error="$errors->first('visible')">
                        <x-lux::input.toggle wire:model="visible" />
                    </x-lux::input.inline-group>
                </form>
            </div>

            <div x-tabs:panel>
                <div class="max-w-xl mx-auto space-y-4">
                    @foreach($translations as $index => $translation)
                        <x-lux::input.rich-text-simple wire:model="translations.{{$index}}" />
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

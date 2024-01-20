<form wire:submit.prevent="save" class="max-w-3xl mx-auto space-y-6">
    <x-lux::locale-selector />

    <x-lux::input.inline-group
        required
        label="Nombre"
        error="{{ $errors->first('name') }}"
    >
        <x-lux::input.text wire:model="name" />
    </x-lux::input.inline-group>

    <x-lux::input.inline-group
        required
        label="Posición"
        error="{{ $errors->first('position') }}"
    >
        <x-lux::input.select native wire:model="position">
            @foreach(Pieldefoca\Lux\Enum\SliderPosition::options() as $value => $label)
                <option value="{{ $value }}">{{ $label }}</option>
            @endforeach
            <option value="otra">Otra</option>
        </x-lux::input.select>
    </x-lux::input.inline-group>

    <div>
        <x-lux::card title="Diapositivas" class="p-4">
            <x-slot name="actions">
                <x-lux::button small x-on:click="$dispatch('new-slide', { slider: {{$slider->id}} })" icon="photo-plus">Nueva diapositiva</x-lux::button>
            </x-slot>

            @if($slider->slides?->isEmpty())
                <div class="flex flex-col items-center justify-center py-6 space-y-6">
                    <x-lux::tabler-icons.eye-off class="w-28 h-28 opacity-10" />
                    <p class="text-stone-500">No hay diapositivas</p>
                    <x-lux::button x-on:click="$dispatch('new-slide', { slider: {{$slider->id}} })" icon="photo-plus">Nueva diapositiva</x-lux::button>
                </div>
            @else
                <div>
                    @foreach($slider->slides as $slide)
                        <div class="flex items-end space-x-4 [&:not(:last-child)]:pb-4 [&:not(:first-child)]:pt-4 [&:not(:last-child)]:border-b border-stone-200">
                            <div>
                                <x-lux::media-preview :media="$slide->getBackground($currentLocaleCode)" class="!w-28" />
                            </div>
                            <div class="flex-grow">
                                <div class="flex items-center space-x-2 min-h-[1rem]">
                                    <div class="flex items-center justify-end space-x-1 w-16">
                                        <span class="text-[8px] font-bold text-stone-500 uppercase">Título</span>
                                        <x-lux::tabler-icons.h-1 class="w-3 h-3 text-stone-400" />
                                    </div>
                                    @if($slide->title)
                                        <p class="text-sm">{{ $slide->translate('title', $currentLocaleCode) }}</p>
                                    @else
                                        <x-lux::tabler-icons.line-dashed class="text-stone-400" />
                                    @endif
                                </div>
                                <div class="flex items-center space-x-2 min-h-[1rem]">
                                    <div class="flex items-center justify-end space-x-1 w-16">
                                        <span class="text-[8px] font-bold text-stone-500 uppercase">Subtítulo</span>
                                        <x-lux::tabler-icons.h-2 class="w-3 h-3 text-stone-400" />
                                    </div>
                                    @if($slide->subtitle)
                                        <p class="text-sm">{{ $slide->translate('subtitle', $currentLocaleCode) }}</p>
                                    @else
                                        <x-lux::tabler-icons.line-dashed class="text-stone-400" />
                                    @endif
                                </div>
                                <div class="flex items-center space-x-2 min-h-[1rem]">
                                    <div class="flex items-center justify-end space-x-1 w-16">
                                        <span class="text-[8px] font-bold text-stone-500 uppercase">Acción</span>
                                        <x-lux::tabler-icons.hand-click class="w-3 h-3 text-stone-400" />
                                    </div>
                                    @if($slide->action_text)
                                        <p class="text-sm">{{ $slide->translate('action_text', $currentLocaleCode) }}</p>
                                    @else
                                        <x-lux::tabler-icons.line-dashed class="text-stone-400" />
                                    @endif
                                </div>
                                <div class="flex items-center space-x-2 min-h-[1rem]">
                                    <div class="flex items-center justify-end space-x-1 w-16">
                                        <span class="text-[8px] font-bold text-stone-500 uppercase">URL</span>
                                        <x-lux::tabler-icons.link class="w-3 h-3 text-stone-400" />
                                    </div>
                                    @if($slide->action_link)
                                        <p class="text-sm">{{ $slide->translate('action_link', $currentLocaleCode) }}</p>
                                    @else
                                        <x-lux::tabler-icons.line-dashed class="text-stone-400" />
                                    @endif
                                </div>
                            </div>
                            <div>
                                <x-lux::button.icon x-on:click="$dispatch('edit-slide', { slide: {{$slide->id}} })" small action="edit" />
                                <x-lux::button.icon x-on:click="$dispatch('delete-slide', { slide: {{$slide->id}} })" small action="delete" />
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </x-lux::card>
    </div>
</form>

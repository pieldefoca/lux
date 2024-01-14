@props([
    'model',
])

<tr
    {{ $attributes->class(['even:bg-stone-50']) }}
    :class="{'selected !bg-sky-100': $wire.$parent.selected.includes('{{$model->id}}')}"
    x-bind:draggable="$wire.$parent.reordering"
    drag-item="{{ $model->id }}"
>
    @if($this->hasBulkActions || $this->reorderable)
        <x-lux::table.td>
            @if($this->hasBulkActions)
                <x-lux::input.checkbox x-show="!$wire.$parent.reordering" wire:model="$parent.selected" value="{{ $model->id }}" />
            @endif

            <div x-show="$wire.$parent.reordering" type="button" class="cursor-grab">
                <x-lux::tabler-icons.grip-horizontal class="w-5 h-5 opacity-50 transition-opacity duration-300 hover:opacity-80" />
            </div>
        </x-lux::table.td>
    @endif

    {{ $slot }}
</tr>

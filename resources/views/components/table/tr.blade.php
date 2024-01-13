@props([
    'model',
])

<tr
    {{
        $attributes->class([
            'even:bg-stone-100',
            'selected bg-stone-50' => $this->hasBulkActions() && in_array($model->id, $this->selected)
        ])
    }}
    @if($this->isReorderable() && $this->isReordering())
        draggable="true"
        drag-item="{{ $model->id }}"
    @endif
>
    @if($this->hasBulkActions() || $this->isReorderable())
        <x-lux::table.td>
            @if($this->hasBulkActions() && !$this->isReordering())
                <x-lux::input.checkbox wire:model.live="selected" value="{{ $model->id }}" />
            @elseif($this->isReordering())
                <div type="button" class="cursor-grab">
                    <x-lux::tabler-icons.grip-horizontal class="w-5 h-5 opacity-50 transition-opacity duration-300 hover:opacity-80" />
                </div>
            @endif
        </x-lux::table.td>
    @endif

    {{ $slot }}
</tr>

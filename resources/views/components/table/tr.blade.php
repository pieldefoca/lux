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
>
    @if($this->hasBulkActions())
        <x-lux::table.td>
            <input type="checkbox" wire:model.live="selected" value="{{ $model->id }}" />
        </x-lux::table.td>
    @endif

    {{ $slot }}
</tr>

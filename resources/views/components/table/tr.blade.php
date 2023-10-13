@props([
    'model',
])

<tr
    @class([
        'even:bg-stone-100',
        'selected bg-stone-50' => $this->hasBulkActions() && in_array($model->id, $this->selected)
    ])
>
    @if($this->hasBulkActions())
        <td>
            <input type="checkbox" wire:model.live="selected" value="{{ $model->id }}" />
        </td>
    @endif

    {{ $slot }}
</tr>

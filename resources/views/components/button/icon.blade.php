@props([
    'action', // add, edit, view, delete
    'small' => false,
])

<button
    type="button"
    {{
        $attributes->class([
            'p-1.5 rounded text-stone-700 transition-colors duration-300',
            'hover:bg-red-100 hover:text-red-500' => $action === 'delete',
            'hover:bg-stone-800 hover:text-stone-100' => $action !== 'delete',
        ])
    }}
>
    @if($action === 'edit')
        <x-lux::tabler-icons.edit @class(['w-5 h-5' => $small]) />
    @elseif($action === 'delete')
        <x-lux::tabler-icons.trash @class(['w-5 h-5' => $small]) />
    @elseif($action === 'view')
        <x-lux::tabler-icons.eye @class(['w-5 h-5' => $small]) />
    @elseif($action === 'add')
        <x-lux::tabler-icons.square-rounded-plus @class(['w-5 h-5' => $small]) />
    @endif
</button>

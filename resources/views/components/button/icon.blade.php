@props([
    'action', // edit, view, delete
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
    <x-tabler-icons.edit />
    @elseif($action === 'delete')
    <x-tabler-icons.trash />
    @elseif($action === 'view')
    <x-tabler-icons.eye />
    @endif
</button>

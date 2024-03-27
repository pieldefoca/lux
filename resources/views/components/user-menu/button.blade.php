@props(['icon', 'label'])

<button {{ $attributes->merge(['type' => 'button'])->class(['flex items-center space-x-2 w-full px-2 py-1.5 text-left']) }}>
    <x-dynamic-component component="lux::tabler-icons.{{$icon}}" class="w-4 h-4" />
    <span>{{ $label }}</span>
</button>
@props([
    'type',
])

@php
use Pieldefoca\Lux\Enum\MediaType;
@endphp

<div 
    @class([
        'inline-block px-1 py-px border rounded text-sm',
        'border-orange-500 bg-orange-100 text-orange-500' => $type === MediaType::Image,
        'border-indigo-500 bg-indigo-100 text-indigo-500' => $type === MediaType::Video,
        'border-purple-500 bg-purple-100 text-purple-500' => $type === MediaType::Pdf,
        'border-teal-500 bg-teal-100 text-teal-500' => $type === MediaType::File,
    ])
>
    {{ $type->forHumans() }}
</div>
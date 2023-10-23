@props([
    'type' => 'any', // any, image, video
    'url' => null,
])

@php
$imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];
$videoExtensions = ['mp4', 'mov', 'wmv', 'webm', 'avi', 'flv', 'mkv'];
$fileExtension = pathinfo($url, PATHINFO_EXTENSION);
$isImage = $url && str($fileExtension)->startsWith($imageExtensions);
$isVideo = $url && str($fileExtension)->startsWith($videoExtensions);
@endphp

<div
    {{
        $attributes->class([
            'grid place-items-center w-48 aspect-square bg-stone-100 border border-stone-200 rounded-lg cursor-pointer overflow-hidden transition-all duration-300 shadow hover:contrast-50'
        ])
    }}
>
    @if(empty($url))
        @if($type === 'any')
            <x-lux::tabler-icons.photo-video class="w-1/2 h-1/2 opacity-20" />
        @elseif($type === 'image')
            <x-lux::tabler-icons.photo class="w-1/2 h-1/2 opacity-20" />
        @elseif($type === 'video')
            <x-lux::tabler-icons.movie class="w-1/2 h-1/2 opacity-20" />
        @endif
    @else
        @if($type === 'image' || $isImage)
            <img src="{{ $url }}" class="w-full h-full object-cover" />
        @elseif($type === 'video' || $isVideo)
            <video src="{{ $url }}" autoplay muted controls class="w-full h-full object-cover"></video>
        @endif
    @endif
</div>

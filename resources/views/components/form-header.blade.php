@props(['withoutLocale' => false])

@php
$showLocale = !$withoutLocale;
@endphp

<div {{ $attributes->class(['flex items-center justify-between']) }}>
    <div>
        @if($showLocale)
            <x-lux::locale-selector />
        @endif
    </div>
    
    <x-lux::required-fields />
</div>
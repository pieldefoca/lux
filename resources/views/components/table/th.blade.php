@props([
    'sortableBy' => null,
    // 'direction' => null,
    'multiColumn' => null,
])

@php
$direction = $this->sorts[$sortableBy] ?? null;
@endphp

<th
    {{ $attributes->merge(['class' => 'px-4 py-3 border-y border-stone-300 text-left'])->only('class') }}
>
    @unless ($sortableBy)
        <span class="text-left text-xs font-bold">{{ $slot }}</span>
    @else
        <button wire:click="sortBy('{{ $sortableBy }}')" {{ $attributes->except('class') }} class="flex items-center space-x-2 text-left text-xs font-bold group focus:outline-none">
            <span>{{ $slot }}</span>

            <span
                @class([
                    'relative flex items-center justify-center w-5 h-5 rounded',
                    'bg-stone-300' => $direction,
                    'group-hover:bg-stone-200' => !$direction,
                ])
            >
                @if ($multiColumn)
                    @if ($direction === 'asc')
                        <svg class="w-3 h-3 group-hover:opacity-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path></svg>
                        <svg class="w-3 h-3 opacity-0 group-hover:opacity-100 absolute" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    @elseif ($direction === 'desc')
                        <div class="opacity-0 group-hover:opacity-100 absolute">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path></svg>
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                        <svg class="w-3 h-3 group-hover:opacity-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    @else
                        <svg class="w-3 h-3 opacity-0 group-hover:opacity-100 transition-opacity duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path></svg>
                    @endif
                @else
                    @if ($direction === 'asc')
                        <x-lux::tabler-icons.chevron-up class="w-3.5 h-3.5" />
                    @elseif ($direction === 'desc')
                        <x-lux::tabler-icons.chevron-down class="w-3.5 h-3.5" />
                    @else
                        <div class="flex flex-col">
                            <x-lux::tabler-icons.chevron-up class="w-3 h-3 -mb-1" />
                            <x-lux::tabler-icons.chevron-down class="w-3 h-3 -mt-0.5" />
                        </div>
                    @endif
                @endif
            </span>
        </button>
    @endif
</th>

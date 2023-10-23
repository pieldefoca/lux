@props([
    'trigger',
    'triggerText' => 'Opciones',
])

@push('js')
<script defer src="https://unpkg.com/@alpinejs/ui@3.13.1-beta.0/dist/cdn.min.js"></script>
@endpush

<div x-data="{ open: false }">
    <div x-menu x-model="open" class="relative">
        @if(isset($trigger))
            {{ $trigger }}
        @else
            <button class="px-3 py-1 rounded-md text-xs bg-white border border-stone-200 text-stone-700 shadow hover:bg-stone-800 hover:text-stone-100" x-menu:button>
                <div class="flex items-center space-x-2">
                    <span>{{ $triggerText }}</span>
                    <x-lux::tabler-icons.chevron-down x-bind:class="{'rotate-180': open}" class="w-3 h-3 text-stone-400" />
                </div>
            </button>
        @endif
    
        <div
            x-menu:items
            x-transition.origin.top.right
            class="absolute right-0 min-w-[200px] mt-2 z-10 origin-top-right bg-white border border-gray-200 divide-y divide-gray-100 rounded-md shadow-md py-1 outline-none"
            x-cloak
        >
            {{ $slot }}
        </div>
    </div>
</div>
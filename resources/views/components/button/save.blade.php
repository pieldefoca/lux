<x-lux::button 
    {{ $attributes }}
    wire:loading.delay.attr="disabled"
>
    <div class="flex items-center space-x-2">
        <div class="flex items-center">
            <svg wire:loading class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <x-lux::tabler-icons.device-floppy wire:loading.remove class="w-5 h-5" />
        </div>

        <div>
            <span wire:loading>{{ trans('lux::lux.saving') }}</span>
            <span wire:loading.remove>{{ trans('lux::lux.save') }}</span>
        </div>
    </div>
</x-lux::button>

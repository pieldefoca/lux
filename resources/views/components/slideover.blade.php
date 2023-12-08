<div x-data="{ open: @entangle('visible') }" class="flex justify-center">
    <div
        x-dialog
        x-model="open"
        style="display: none"
        class="fixed inset-0 overflow-hidden z-10"
    >
        <!-- Overlay -->
        <div x-dialog:overlay x-transition.opacity class="fixed inset-0 bg-black/30 backdrop-blur-[1px]"></div>
 
        <!-- Panel -->
        <div class="fixed inset-y-0 right-0 p-4 max-w-lg w-full">
            <div
                x-dialog:panel
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="translate-x-full"
                x-transition:enter-end="translate-x-0"
                x-transition:leave="transition ease-in duration-300"
                x-transition:leave-start="translate-x-0"
                x-transition:leave-end="translate-x-full"
                class="h-full w-full"
            >
                <div class="relative h-full flex flex-col justify-between px-6 pt-4 rounded-md bg-white shadow-lg overflow-y-auto"> 
                    <!-- Body -->
                    <div>
                        <!-- Title -->
                        <div class="flex items-center justify-between pb-2 mb-2 border-b border-stone-200">
                            <h2 x-dialog:title class="text-xl font-bold">{{ $title }}</h2>

                            <button 
                                @click="$dialog.close()" 
                                type="button" 
                                class="rounded-md p-px text-stone-400 outline-none transition-colors duration-300 hover:text-stone-800 focus:text-stone-800 focus-visible:ring-2 focus-visible:ring-black"
                            >
                                <span class="sr-only">Close slideover</span>
                                <x-lux::tabler-icons.x />
                            </button>    
                        </div>
 
                        <!-- Content -->
                        <div>
                            {{ $slot }}
                        </div>
                    </div>
 
                    <!-- Footer -->
                    <div class="py-4 mt-6 border-t border-stone-200">
                        {{ $footer }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
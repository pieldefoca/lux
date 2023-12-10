<div x-data="{ open: @entangle('visible') }" class="flex justify-center">
    <!-- Modal -->
    <div
        x-show="open"
        style="display: none"
        x-on:keydown.escape.prevent.stop="open = false"
        role="dialog"
        aria-modal="true"
        x-id="['modal-title']"
        :aria-labelledby="$id('modal-title')"
        class="fixed inset-0 z-10 overflow-y-auto"
    >
        <!-- Overlay -->
        <div x-show="open" x-transition.opacity class="fixed inset-0 bg-black bg-opacity-50"></div>

        <!-- Panel -->
        <div
            x-show="open" x-transition
            x-on:click="open = false"
            class="relative flex min-h-screen items-center justify-center p-4"
        >
            <div
                x-on:click.stop
                x-trap.noscroll.inert="open"
                class="relative w-full max-w-4xl max-h-[90vh] overflow-y-auto rounded-xl bg-white p-6 shadow-lg"
            >
                <!-- Title -->
                <h2 class="text-2xl font-bold mb-6" :id="$id('modal-title')">{{ $title }}</h2>

                <!-- Content -->
                {{ $slot }}

                <!-- Buttons -->
                @isset($footer)
                <div class="mt-6 pt-4 flex space-x-2 border-t-2 border-stone-100">
                    {{ $footer ?? '' }}
                </div>
                @endisset
            </div>
        </div>
    </div>
</div>

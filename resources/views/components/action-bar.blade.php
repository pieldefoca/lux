@props(['withoutLocale' => false, 'centered' => false])

<div
        x-data="{
            errorMessage: null,
            init() {
                $wire.$on('show-error-feedback', (params) => {
                    this.errorMessage = params.message ?? 'Corrige los errores'
                })
            },
        }"
        class="sticky bottom-0 bg-transparent backdrop-blur-sm pb-6 mt-8"
    >
        <div @class(["flex items-center border border-gray-100 bg-white shadow-sm rounded-lg p-3", 'justify-center' => $centered, 'justify-between' => !$centered]) class="">
            <div>
                @unless($withoutLocale)
                    <x-lux::locale-selector />
                @endunless
            </div>

            <div class="flex items-center space-x-8">
                <p x-show="errorMessage" class="flex items-center space-x-1 text-sm text-red-400">
                    <x-lux::tabler-icons.exclamation-circle />
                    <span x-text="errorMessage"></span>
                </p>

                {{ $slot }}
            </div>
        </div>
    </div>
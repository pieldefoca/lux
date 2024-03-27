<div
    x-data="{
        errorMessage: null,
        init() {
            $wire.$on('show-error-feedback', (params) => {
                this.errorMessage = params.message ?? 'Corrige los errores'
            })
        },
    }"
    class="sticky bottom-0 bg-transparent backdrop-blur-sm pb-6 mt-8 z-10"
>
    <div @class(['relative grid grid-cols-1 md:grid-cols-3 border border-gray-100 bg-white shadow-sm rounded-lg p-3'])>
        <div class="self-center">
            {{ $leftSide ?? '' }}
        </div>

        <div class="justify-self-center self-center">
            {{ $slot }}
        </div>


        <div class="justify-self-end self-center">
            <p x-show="errorMessage" class="flex items-center space-x-1 text-sm text-red-400">
                <x-lux::tabler-icons.exclamation-circle />
                <span x-text="errorMessage"></span>
            </p>

            <p wire:dirty.flex wire:dirty.class="flex" class="hidden items-center space-x-1 text-sm text-orange-400">
                <x-lux::tabler-icons.alert-triangle />
                <span>{{ trans('lux::lux.you_have_unsaved_changes') }}</span>
            </p>
        </div>
    </div>
</div>
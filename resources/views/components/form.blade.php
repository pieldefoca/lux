<div
    x-data="{
        init() {
            $wire.$watch('', () => {
                $store.lux.setDirtyState(true)
            })
        }
    }"
    {{ $attributes }}
>
    {{ $slot }}
</div>
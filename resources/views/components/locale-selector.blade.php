<div
    x-data="{
        open: false,
        select(locale) {
            $store.lux.selectLocale(locale)
            this.open = false
        }
    }"
    @click.outside="open = false"
    class="flex items-center justify-center"
>
    <div class="relative flex items-center py-1 border border-gray-300 rounded-md transition-all duration-200 hover:border-gray-400 hover:bg-gray-50 hover:shadow">
        <button x-ref="button" @click="open = !open" class="flex items-center pl-4 pr-2" type="button">
            <span class="text-xs opacity-50">Idioma</span>
            <div class="flex items-center space-x-2 ml-6">
                <img :src="`/vendor/lux/img/flags/${$store.lux.locale}.svg`" class="w-4 h-4" />
                <span class="text-sm uppercase" x-text="$store.lux.locale"></span>
            </div>
            <div class="ml-2">
                <x-lux::tabler-icons.chevron-down x-bind:class="{'rotate-180': open}" class="w-4 h-4 opacity-40 transition-transform" />
            </div>
        </button>

        <div x-show="open" x-anchor.bottom.offset.10="$refs.button" class="absolute top-full flex flex-col w-40 mt-1 p-1 bg-white rounded-md shadow z-10">
            @foreach(config('lux.locales') as $locale)
                <button @click="select('{{$locale}}')" class="flex items-center justify-between space-x-1 uppercase pl-3 pr-1.5 py-1.5 rounded hover:bg-black hover:text-white" type="button">
                    <div class="flex items-center space-x-2">
                        <img :src="`/vendor/lux/img/flags/{{$locale}}.svg`" class="w-4 h-4" />
                        <span class="text-sm uppercase">{{ $locale }}</span>
                    </div>
                    @if($locale === config('lux.default_locale'))
                        <span class="text-xs opacity-40 normal-case">Por defecto</span>
                    @endif
                </button>
            @endforeach
        </div>
    </div>
</div>

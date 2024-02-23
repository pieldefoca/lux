@props([
    'page',
])

<a
    href="{{ route('lux.pages.edit', $page) }}"
    class="group relative flex items-center justify-between p-3 border border-stone-300 rounded-md transition-all duration-300 hover:scale-[1.02] hover:bg-stone-50 hover:border-stone-600"
>
    <div>
        <div class="flex items-center space-x-4">
            <p class="relative after:absolute after:bottom-0 after:left-0 after:w-0 after:h-px after:bg-stone-800 after:transition-all after:duration-300 group-hover:after:w-4/5">{{ $page->name }}</p>

            @if($page->is_home_page)
                <span class="border border-sky-200 bg-sky-100 rounded text-[8px] text-sky-500 px-2 mt-1">{{ trans('lux::lux.home-page') }}</span>
            @endif
        </div>

        <p x-data="{ page: @js($page) }" class="flex text-[9px] text-stone-500 transition-colors duration-300 hover:text-black">
            <span>{{ config('app.url') }}/</span>
            {{-- <span>{{ $page->translate('key', $this->locale) }}</span> --}}
        </p>
    </div>

    <div class="flex items-center space-x-6">
        <div class="flex items-center space-x-2">
            <button
                x-data="{
                    visible: @js($page->visible),
                }"
                @click.stop.prevent="
                    if(visible) {
                        Swal.fire({
                            title: 'Ocultar página',
                            text: 'Al ocultar una página es posible que algunos enlaces dejen de funcionar, ¿seguro que quieres continuar?',
                            icon: 'warning',
                            showCancelButton: true,
                            customClass: {
                                confirmButton: 'px-4 py-2 rounded-lg border-2 border-red-500 bg-red-100 text-red-500 transition-colors duration-300 hover:bg-red-200 hover:border-red-600 hover:text-red-600',
                                cancelButton: 'hover:underline',
                                actions: 'space-x-6',
                            },
                            buttonsStyling: false,
                            confirmButtonText: 'Sí, ocultar',
                            cancelButtonText: 'Cancelar',
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $wire.call('toggleVisibility', { page: {{ $page->id }} })
                            }
                        })
                    } else {
                        $wire.call('toggleVisibility', { page: {{ $page->id }} })
                    }
                "
                type="button"
                @class([
                    'group/visibility px-1 border rounded text-[9px] transition-all duration-300',
                    'border-green-500 bg-green-100 text-green-500 hover:border-stone-500 hover:bg-stone-100 hover:text-stone-500' => $page->visible,
                    'border-stone-500 bg-stone-100 hover:border-green-500 hover:bg-green-100 hover:text-green-500' => !$page->visible,
                ])
            >
                @if($page->visible)
                    <div class="flex items-center space-x-1 group-hover/visibility:hidden">
                        <span class="relative flex h-1.5 w-1.5">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-1.5 w-1.5 bg-green-500"></span>
                        </span>
                        <span>Visible</span>
                    </div>
                    <div class="hidden group-hover/visibility:flex items-center space-x-1">
                        <x-lux::tabler-icons.eye-off class="w-3 h-3" />
                        <span>Ocultar</span>
                    </div>
                @else
                    <div class="flex items-center space-x-1 group-hover/visibility:hidden">
                        <span class="relative flex h-2 w-2">
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-stone-500"></span>
                        </span>
                        <span>Oculta</span>
                    </div>
                    <div class="hidden group-hover/visibility:flex items-center space-x-1">
                        <x-lux::tabler-icons.eye class="w-3 h-3 text-green-400" />
                        <span>Mostrar</span>
                    </div>
                @endif
            </button>

            {{-- <x-lux::button.icon @click.stop.prevent="location.href = '{{ route($page->id) }}'" action="view"/> --}}
        </div>
    </div>
</a>

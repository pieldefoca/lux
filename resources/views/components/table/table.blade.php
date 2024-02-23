@props([
    'emptyIcon' => 'search-off',
    'emptyMessage' => 'No hay resultados',
])

<div>
    <x-lux::card>
        <div @class(['rounded-t-md px-4 py-4'])>
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    @if($this->reorderable)
                        <div>
                            <button x-show="$wire.reordering" wire:click="finishReordering" type="button" class="flex items-center space-x-px px-1 py-px rounded transition-colors duration-300 hover:bg-black hover:text-white">
                                <x-lux::tabler-icons.check class="w-5 h-5" />
                                <span class="text-xs">Aceptar</span>
                            </button>

                            <button x-show="!$wire.reordering" @click="$wire.reordering = true" title="Reordenar" type="button" class="group flex flex-col items-center p-1 rounded text-stone-600 -translate-x-1 transition-colors duration-300 hover:bg-black">
                                <x-lux::tabler-icons.reorder class="w-5 h-5 transition-colors duration-300 group-hover:text-white" />
                            </button>
                        </div>
                    @endif

                    @if($this->searchable)
                        <x-lux::input.search wire:model.live.debounce="search" />
                    @endif

                    @if($this->hasFilters)
                        <div>
                            <x-lux::popover>
                                <x-lux::popover.button class="flex items-center gap-2 rounded-md border p-1.5 text-gray-600 text-sm">
                                    <div class="flex items-center space-x-3">
                                        @if($this->activeFilters > 0)
                                            <x-lux::tabler-icons.filter-filled class="w-5 h-5 text-stone-800" />
                                        @else
                                            <x-lux::tabler-icons.filter class="w-5 h-5" />
                                        @endif
                                        <div class="flex items-center space-x-2">
                                            <p class="flex items-center space-x-1">
                                                <span>Filtros</span>
                                                @if(($count = $this->activeFilters) > 0) <span class="flex items-center justify-center w-4 h-4 bg-black text-white text-[10px] rounded-full">{{ $count }}</span>@endif
                                            </p>
                                            <x-lux::tabler-icons.selector class="w-4 h-4 opacity-50" />
                                        </div>
                                    </div>
                                </x-lux::popover.button>

                                <x-lux::popover.panel class="border border-gray-100 shadow-xl z-10 w-64 p-4 overflow-hidden">
                                    {{ $filters ?? '' }}
                                </x-lux::popover.panel>
                            </x-lux::popover>
                        </div>
                    @endif
                </div>

                <div class="flex items-center space-x-4">
                    <div class="flex items-center">
                        <div x-show="$wire.selected.length" class="flex items-center space-x-4">
                            <p x-text="$wire.selected.length === 1 ? '1 fila seleccionada' : $wire.selected.length + ' filas seleccionadas'" class="text-sm"></p>

                            <div class="h-6 w-px bg-stone-200"></div>

                            <div class="flex items-center space-x-3">
                                <x-lux::table.bulk-delete-button
                                    x-on:click.stop.prevent="
                                        Swal.fire({
                                            title: 'Eliminar filas',
                                            text: '¿Seguro que quieres eliminar las filas seleccionadas?',
                                            icon: 'warning',
                                            showCancelButton: true,
                                            customClass: {
                                                confirmButton: 'px-4 py-2 rounded-lg border-2 border-red-500 bg-red-100 text-red-500 transition-colors duration-300 hover:bg-red-200 hover:border-red-600 hover:text-red-600',
                                                cancelButton: 'hover:underline',
                                                actions: 'space-x-6',
                                            },
                                            buttonsStyling: false,
                                            confirmButtonText: 'Eliminar',
                                            cancelButtonText: 'Cancelar',
                                        }).then((result) => {
                                            if (result.isConfirmed) {
                                                $wire.call('deleteSelected')
                                            }
                                        })
                                    "
                                />

                                <x-lux::table.bulk-actions>
                                    {{ $bulkActions ?? '' }}
                                </x-lux::table.bulk-actions>
                            </div>
                        </div>

                        <div class="flex items-center space-x-3">
                            {{ $actions ?? '' }}

                            @if($this->showLocale)
                                <x-lux::locale-selector class="mb-8" />
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if($this->rows->isEmpty())
            <div class="flex flex-col items-center justify-center space-y-6 py-8">
                <x-dynamic-component :component="'lux::tabler-icons.' . $emptyIcon" class="w-28 h-28 opacity-10" />
                <p class="text-stone-500">{{ $emptyMessage }}</p>
                {{ $createFirstButton ?? '' }}
            </div>
        @else
            <table class="admin-table w-full">
                <thead>
                    <tr class="bg-stone-100">
                        @if($this->hasBulkActions || $this->reorderable)
                            <x-lux::table.th class="w-4">
                                @if($this->hasBulkActions)
                                    <x-lux::input.checkbox x-show="!$wire.reordering" wire:model.live="selectPage" />
                                @endif
                            </x-lux::table.th>
                        @endif

                        {{ $head }}
                    </tr>
                </thead>

                <tbody @if($this->reorderable && $this->reordering) drag-root="reorder" @endif>
                    {{ $body }}
                </tbody>
            </table>

            <div class="p-4 flex items-center w-full space-x-6">
                <select wire:model.live="perPage" class="w-24 px-2 py-1 border border-stone-300 rounded-md">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>

                <div class="flex-grow">
                    {{ $this->rows->links('lux::pagination') }}
                </div>
            </div>
        @endif
    </x-lux::card>
</div>

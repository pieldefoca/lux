@props([
    'emptyIcon' => 'search-off',
    'emptyMessage' => 'No hay resultados',
])

<div>
    <x-lux::locale-selector />

    <x-lux::card>
        <div
            @class([
                'rounded-t-md px-4 py-4',
                'bg-amber-50' => $this->hasBulkActions() && $this->hasAnyRowSelected()
            ])
        >
            @if ($this->hasBulkActions() && $this->hasAnyRowSelected())
                <div class="flex items-center justify-between">
                    <div class="text-xs">
                        @unless ($this->areAllRowsSelected())
                            <div>
                                <span>
                                    <strong>{{ $this->rows->count() }}</strong>
                                    <span class="lowercase">{{ trans_choice('lux::lux.selected-rows', $this->rows->count()) }}</span>.
                                </span>
                                <button wire:click="selectAll" class="underline">Seleccionar las {{ $this->rows->total() }} filas.</button>
                            </div>
                        @else
                            <div class="flex items-center">
                                <span>
                                    <strong>{{ $this->rows->total() }}</strong>
                                    <span class="lowercase">{{ trans_choice('lux::lux.selected-rows', $this->rows->count()) }}</span>.
                                </span>
                                
                                <x-lux::button.link wire:click="clearSelection" class="relative ml-1 text-xs">Deseleccionar todo</x-lux::button.link>
                            </div>
                        @endif
                    </div>

                    <div class="flex items-center space-x-3">
                        @isset($bulkActions)
                            <x-lux::dropdown trigger-text="Acciones">
                                {{ $bulkActions }}
                            </x-lux::dropdown>
                        @endisset
                        <x-lux::table.bulk-delete-button
                            x-on:click.stop.prevent="
                                Swal.fire({
                                    title: 'Eliminar categorías del blog',
                                    text: '¿Seguro que quieres eliminar las categorías seleccionadas?',
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
                    </div>
                </div>
            @else
                <div class="flex items-center justify-between">
                    <x-lux::input.search wire:model.live.debounce="filters.search" />

                    <div>
                        <div>
                            <x-lux::dropdown></x-lux::dropdown>
                        </div>

                        {{ $actions ?? '' }}
                    </div>
                </div>
            @endif
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
                        @if($this->hasBulkActions())
                        <x-lux::table.th>
                            <input type="checkbox" wire:model.live="selectPage" />
                        </x-lux::table.th>
                        @endif

                        {{ $head }}
                    </tr>
                </thead>

                <tbody>
                    {{ $body }}
                </tbody>
            </table>

            <div class="p-4 flex items-center w-full space-x-6">
                <select wire:model.live="perPage" class="px-2 py-1 border border-stone-300 rounded-md">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>

                <div class="flex-grow">
                    {{-- {{ $this->rows->links('lux::pagination') }} --}}
                </div>
            </div>
        @endif

    </x-lux::card>
</div>

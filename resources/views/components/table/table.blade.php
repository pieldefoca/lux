@props([
    'emptyIcon' => 'search-off',
    'emptyMessage' => 'No hay resultados',
])

@if($this->rows->isEmpty())
    <x-admin.card>
        <div class="flex flex-col items-center justify-center space-y-6 py-8">
            <x-dynamic-component :component="'admin.tabler-icons.' . $emptyIcon" class="w-28 h-28 opacity-10" />
            <p class="text-stone-500">{{ $emptyMessage }}</p>
            {{ $createFirstButton ?? '' }}
        </div>
    </x-admin.card>
@else
    <div>
        <x-admin.locale-selector />

        <x-admin.card without-padding>
            <div
                @class([
                    'p-4',
                    'border-2 border-dashed border-stone-800 rounded-t-md bg-stone-100' => $this->hasBulkActions() && $this->selectPage
                ])
            >
                @if ($this->hasBulkActions() && $this->selectPage)
                    <div class="flex items-center justify-between">
                        <div class="text-xs">
                            @unless ($this->selectAll)
                            <div>
                                <span>
                                    <strong>{{ $this->rows->count() }}</strong>
                                    <span class="lowercase">{{ trans_choice('admin.selectedRows', $this->rows->count()) }}</span>.
                                </span>
                                <button wire:click="selectAll" class="underline">Seleccionar las {{ $this->rows->total() }} filas.</button>
                            </div>
                            @else
                            <div>
                                <span>
                                    <strong>{{ $this->rows->total() }}</strong>
                                    <span class="lowercase">{{ trans_choice('admin.selectedRows', $this->rows->count()) }}</span>.
                                </span>
                                <button wire:click="unselectAll" class="ml-1">Deseleccionar todo.</button>
                            </div>
                            @endif
                        </div>

                        <div x-data>
                            <x-admin.dropdown>
                                <x-admin.dropdown.item>
                                    <x-admin.input.group label="Cambiar estado">
                                        <select></select>
                                    </x-admin.input.group>
                                </x-admin.dropdown.item>
                            </x-admin.dropdown>
                            <x-admin.table.bulk-delete-button
                                x-on:click="
                                    sliders = []
                                    rows = await $wire.$call('getRowsProperty')
                                    sliders = rows.data.filter(slider => $wire.selected.includes(slider.id.toString()))
                                "
                            />
                        </div>
                    </div>
                @else
                    <div class="flex items-center justify-between">
                        <div class="group relative inline-block">
                            <input
                                type="text"
                                wire:model.live.debounce="filters.search"
                                placeholder="Buscar..."
                                class="w-72 rounded-md border-2 border-stone-300 px-2 py-2 text-xs outline-none transition-colors duration-300 hover:border-stone-800 hover:bg-stone-100 focus:border-stone-800 focus:bg-stone-100 focus:ring-2 focus:ring-stone-300"
                            />
                            <x-lux::tabler-icons.search class="absolute top-1/2 right-3 -translate-y-1/2 w-5 h-5 opacity-50 transition-opacity group-hover:opacity-100" />
                        </div>

                        <div>
                            <div>

                            </div>

                            {{ $actions ?? '' }}
                        </div>
                    </div>
                @endif
            </div>

            <table class="admin-table w-full">
                <thead>
                    <tr class="bg-stone-100">
                        @if($this->hasBulkActions())
                        <th>
                            <input type="checkbox" wire:model.live="selectPage" />
                        </th>
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
                    {{ $this->rows->links('admin.pagination') }}
                </div>
            </div>
        </x-admin.card>
    </div>
@endif

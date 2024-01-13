<?php

namespace Pieldefoca\Lux\Livewire\Locales;

use Livewire\Attributes\Computed;
use Pieldefoca\Lux\Models\Locale;
use Pieldefoca\Lux\Livewire\LuxTable;
use Pieldefoca\Lux\Livewire\Table\Traits\Searchable;
use Pieldefoca\Lux\Livewire\Table\Traits\WithBulkActions;

class Table extends LuxTable
{
    use WithBulkActions;

    public $model = Locale::class;

    #[Computed]
    public function rowsQuery()
    {
        return Locale::query();
    }

    public function render()
    {
        return view('lux::livewire.locales.table');
    }
}
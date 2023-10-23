<?php

namespace Pieldefoca\Lux\Livewire;

use Pieldefoca\Lux\Livewire\Table\Traits\WithPagination;
use Pieldefoca\Lux\Livewire\Table\Traits\WithBulkActions;
use Pieldefoca\Lux\Traits\UsesLocale;

class LuxTable extends LuxComponent
{
    use WithPagination;
    
    public function isSearchable(): bool
    {
        return in_array(Searchable::class, class_uses_recursive($this));
    }

    public function hasBulkActions(): bool
    {
        return in_array(WithBulkActions::class, class_uses_recursive($this));
    }
}
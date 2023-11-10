<?php

namespace Pieldefoca\Lux\Livewire;

use Pieldefoca\Lux\Livewire\Table\Traits\Reorderable;
use Pieldefoca\Lux\Livewire\Table\Traits\WithPagination;
use Pieldefoca\Lux\Livewire\Table\Traits\WithBulkActions;

class LuxTable extends LuxComponent
{
    use WithPagination;

    public $reordering = false;

    public function isSearchable(): bool
    {
        return in_array(Searchable::class, class_uses_recursive($this));
    }

    public function hasBulkActions(): bool
    {
        return in_array(WithBulkActions::class, class_uses_recursive($this));
    }

    public function isReorderable()
    {
        return in_array(Reorderable::class, class_uses_recursive($this));
    }

    public function isReordering()
    {
        return $this->isReorderable() && $this->reordering;
    }
}

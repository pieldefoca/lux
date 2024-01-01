<?php

namespace Pieldefoca\Lux\Livewire;

use Livewire\Attributes\Computed;
use Pieldefoca\Lux\Livewire\Table\Traits\Searchable;
use Pieldefoca\Lux\Livewire\Table\Traits\Reorderable;
use Pieldefoca\Lux\Livewire\Table\Traits\WithFilters;
use Pieldefoca\Lux\Livewire\Table\Traits\WithSorting;
use Pieldefoca\Lux\Livewire\Table\Traits\WithPagination;
use Pieldefoca\Lux\Livewire\Table\Traits\WithBulkActions;

class LuxTable extends LuxComponent
{
    use WithSorting;
    use WithPagination;
    use WithFilters;

    public $reordering = false;

    #[Computed]
    public function rows()
    {
        $this->applySorting($this->rowsQuery);

        return $this->applyPagination($this->rowsQuery);
    }

    public function isSearchable(): bool
    {
        return in_array(Searchable::class, class_uses_recursive($this));
    }

    public function hasBulkActions(): bool
    {
        return in_array(WithBulkActions::class, class_uses_recursive($this));
    }

    public function hasBulkDeletion(): bool
    {
        return $this->hasBulkActions() && $this->allowBulkDeletion;
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

<?php

namespace Pieldefoca\Lux\Livewire\Table\Traits;

use Livewire\Attributes\Computed;
use Pieldefoca\Lux\Traits\UsesLocale;
use Pieldefoca\Lux\Livewire\Table\Traits\WithSorting;
use Pieldefoca\Lux\Livewire\Table\Traits\WithPagination;

trait LuxTable
{
    use WithSorting;
    use WithPagination;

    public $showLocale = false;
    public $hasBulkActions = false;
    public $hasBulkDeletion = false;
    public $searchable = false;
    public $reorderable = false;
    public $reordering = false;
    public $hasFilters = false;

    public function mountLuxTable()
    {
        $this->searchable = in_array(Searchable::class, class_uses_recursive($this));
        $this->hasBulkActions = in_array(WithBulkActions::class, class_uses_recursive($this));
        $this->hasBulkDeletion = $this->hasBulkActions && $this->allowBulkDeletion;
        $this->reorderable = in_array(Reorderable::class, class_uses_recursive($this));
        $this->hasFilters = property_exists($this, 'filters');
    }

    public function updatedFilters()
    {
        $this->resetPage();
    }

    #[Computed]
    public function activeFilters()
    {
        if(! $this->hasFilters) return 0;

        return collect($this->filters)->filter(function($filter) {
            return !empty($filter) || (empty($filter) && $filter === false);
        })->count();
    }

    #[Computed]
    public function rows()
    {
        $this->applySorting($this->rowsQuery);

        return $this->applyPagination($this->rowsQuery);
    }
}
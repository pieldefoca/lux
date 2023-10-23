<?php

namespace Pieldefoca\Lux\Livewire\Table\Traits;

use Livewire\WithPagination as LivewirePagination;

trait WithPagination
{
    use LivewirePagination;

    public $perPage = 10;

    public function mountWithPerPagePagination()
    {
        $this->perPage = session()->get('perPage', $this->perPage);
    }

    public function updatedPerPage($value)
    {
        session()->put('perPage', $value);
    }

    public function applyPagination($query)
    {
        return $query->paginate($this->perPage);
    }
}

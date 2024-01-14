<?php

namespace Pieldefoca\Lux\Livewire\Table\Traits;

trait Searchable
{
    public $search = '';

    public function updatedSearch()
    {
        $this->resetPage();
    }
}
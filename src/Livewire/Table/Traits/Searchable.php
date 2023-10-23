<?php

namespace Pieldefoca\Lux\Livewire\Table\Traits;

trait Searchable
{
    public function mountSearchable()
    {
        $this->addFilter('search', '');
    }
}
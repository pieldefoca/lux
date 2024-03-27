<?php

namespace Pieldefoca\Lux\Livewire\Table\Traits;

trait WithFilters
{
    public $filters = [];

    protected function addFilter($name, $value)
    {
        $this->filters[$name] = $value;
    }
}
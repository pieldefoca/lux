<?php

namespace Pieldefoca\Lux\Livewire\Attributes;

use Livewire\Attribute as LivewireAttribute;

#[\Attribute]
class Media extends LivewireAttribute
{
    public function __construct(
        public string $collection,
        public bool $multiple = false,
    ) {}

    // public function render()
    // {
    //     $currentValue = $this->getValue();

    //     if(empty($currentValue)) {
    //         $currentValue = [];
    //     }

    //     $this->setValue($currentValue);
    // }

    public function isMultiple()
    {
        return $this->multiple;
    }
}
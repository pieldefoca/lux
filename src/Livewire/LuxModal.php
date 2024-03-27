<?php

namespace Pieldefoca\Lux\Livewire;

class LuxModal extends LuxComponent
{
    public $visible = false;

    public function updatedVisible($value)
    {
        if($value === false) {
            $this->clearValidation();
        }
    }

    public function show(): void
    {
        $this->visible = true;
    }

    public function hide(): void
    {
        $this->clearValidation();

        $this->visible = false;
    }
}

<?php

namespace Pieldefoca\Lux\Livewire;

class LuxModal extends LuxComponent
{
    public $visible = false;

    public function show(): void
    {
        $this->visible = true;
    }

    public function hide(): void
    {
        $this->visible = false;
    }
}

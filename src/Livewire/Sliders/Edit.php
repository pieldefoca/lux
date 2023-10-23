<?php

namespace Pieldefoca\Lux\Livewire\Sliders;

use Pieldefoca\Lux\Livewire\LuxComponent;
use Pieldefoca\Lux\Models\Slider;

class Edit extends LuxComponent
{
    public Slider $slider;

    public function render()
    {
        return view('lux::livewire.sliders.edit');
    }
}

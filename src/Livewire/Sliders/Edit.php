<?php

namespace Pieldefoca\Lux\Livewire\Sliders;

use Pieldefoca\Lux\Models\Slide;
use Pieldefoca\Lux\Models\Slider;
use Pieldefoca\Lux\Livewire\LuxComponent;

class Edit extends LuxComponent
{
    public Slider $slider;

    public function deleteSlide(Slide $slide)
    {
        $slide->delete();
    }

    public function render()
    {
        return view('lux::livewire.sliders.edit');
    }
}

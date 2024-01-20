<?php

namespace Pieldefoca\Lux\Livewire\Sliders;

use Livewire\Component;
use Pieldefoca\Lux\Models\Slider;
use Pieldefoca\Lux\Livewire\Table\Traits\LuxTableRow;

class Row extends Component
{
    use LuxTableRow;

    public Slider $slider;

    public function delete()
    {
        dd('deleting ' . $this->user->id);
    }

    public function render()
    {
        return view('lux::livewire.sliders.row');
    }
}
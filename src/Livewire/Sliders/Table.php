<?php

namespace Pieldefoca\Lux\Livewire\Sliders;

use Livewire\Attributes\Computed;
use Pieldefoca\Lux\Livewire\LuxTable;
use Pieldefoca\Lux\Models\Slider;

class Table extends LuxTable
{
    protected $listeners = [
        'sliders-updated' => '$refresh',
    ];

    #[Computed]
    public function rows()
    {
        return Slider::paginate();
    }

    public function render()
    {
        return view('lux::livewire.sliders.table');
    }
}
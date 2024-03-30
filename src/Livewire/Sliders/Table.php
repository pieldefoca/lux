<?php

namespace Pieldefoca\Lux\Livewire\Sliders;

use Livewire\Attributes\Computed;
use Pieldefoca\Lux\Models\Slider;
use Pieldefoca\Lux\Livewire\LuxComponent;
use Pieldefoca\Lux\Livewire\Table\Traits\LuxTable;

class Table extends LuxComponent
{
    use LuxTable;

    public $model = Slider::class;

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
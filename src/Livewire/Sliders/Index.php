<?php

namespace Pieldefoca\Lux\Livewire\Sliders;

use Pieldefoca\Lux\Livewire\LuxComponent;

class Index extends LuxComponent
{
    public function render()
    {
        return view('lux::livewire.sliders.index')
            ->layout('lux::components.layouts.app', [
                'title' => trans('lux::lux.sliders-title'),
                'subtitle' => trans('lux::lux.sliders-subtitle'),
            ]);
    }
}

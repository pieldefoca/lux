<?php

namespace Pieldefoca\Lux\Livewire\Sliders;

use Pieldefoca\Lux\Models\Slide;
use Pieldefoca\Lux\Models\Slider;
use Pieldefoca\Lux\Livewire\LuxComponent;

class Edit extends LuxComponent
{
    public Slider $slider;

    public function render()
    {
        return view('lux::livewire.sliders.edit')
            ->layout('lux::components.layouts.app', [
                'title' => trans('lux::lux.edit-slider-title'),
                'subtitle' => trans('lux::lux.edit-slider-subtitle', ['name' => $this->slider->name]),
            ]);
    }
}

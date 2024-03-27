<?php

namespace Pieldefoca\Lux\Livewire\Pages;

use Pieldefoca\Lux\Models\Page;
use Pieldefoca\Lux\Livewire\LuxComponent;

class Index extends LuxComponent
{
    public function render()
    {
        return view('lux::livewire.pages.index', [
            'pages' => Page::orderBy('name')->get(),
        ]);
    }
}
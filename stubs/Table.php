<?php

namespace Pieldefoca\Lux\Livewire;

use Livewire\Attributes\Computed;

class Table extends LuxTable
{
    #[Computed]
    public function rows()
    {
        //
    }

    public function render()
    {
        return view('lux::livewire.');
    }
}
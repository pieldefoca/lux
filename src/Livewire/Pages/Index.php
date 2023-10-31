<?php

namespace Pieldefoca\Lux\Livewire\Pages;

use Livewire\Attributes\Js;
use Pieldefoca\Lux\Models\Page;
use Livewire\Attributes\Computed;
use Pieldefoca\Lux\Livewire\LuxComponent;

class Index extends LuxComponent
{
    #[Computed]
    public function pages()
    {
        return Page::all();
    }

    public function render()
    {
        return view('lux::livewire.pages.index');
    }
}

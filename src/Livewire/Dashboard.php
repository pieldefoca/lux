<?php

namespace Pieldefoca\Lux\Livewire;

use Livewire\Component;

class Dashboard extends Component
{
    public $text = '<p>cosas</p>';

    public function save()
    {
        dd($this->text);
    }

	public function render()
	{
		return view('lux::livewire.dashboard');
	}
}

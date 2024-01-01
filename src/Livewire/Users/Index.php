<?php

namespace Pieldefoca\Lux\Livewire\Users;

use Illuminate\Foundation\Auth\User;
use Pieldefoca\Lux\Livewire\LuxComponent;

class Index extends LuxComponent
{
    public function render()
    {
        return view('lux::livewire.users.index');
    }
}

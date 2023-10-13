<?php

namespace Pieldefoca\Lux\Livewire;

use Livewire\Component;
use Pieldefoca\Lux\Traits\UsesLocale;

class LuxComponent extends Component
{
    use UsesLocale;

    public function notifySuccess(string $message)
    {
        $this->dispatch('notify-success', message: $message);
    }
}

<?php

namespace Pieldefoca\Lux\Livewire;

use Livewire\Component;
use Pieldefoca\Lux\Traits\Notifies;
use Pieldefoca\Lux\Traits\UsesLocale;

class LuxComponent extends Component
{
    use UsesLocale;
    use Notifies;
}

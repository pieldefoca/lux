<?php

namespace Pieldefoca\Lux\Livewire\Table\Traits;

use Livewire\Attributes\Reactive;
use Pieldefoca\Lux\Traits\Notifies;

trait LuxTableRow
{
    use Notifies;

    #[Reactive]
    public $locale;

    public $hasBulkActions = false;

    public $reorderable = false;

    public $reordering = false;
}
<?php

namespace Pieldefoca\Lux\Livewire\Users;

use Livewire\Component;
use Livewire\Attributes\Computed;
use Illuminate\Foundation\Auth\User;
use Pieldefoca\Lux\Livewire\LuxTable;
use Pieldefoca\Lux\Livewire\Table\Traits\Searchable;
use Pieldefoca\Lux\Livewire\Table\Traits\WithBulkActions;

class Row extends Component
{
    public $user;

    public $hasBulkActions = false;

    public $reorderable = false;

    public $reordering = false;

    public function delete()
    {
        dd('deleting ' . $this->user->id);
    }

    public function render()
    {
        return view('lux::livewire.users.row');
    }
}
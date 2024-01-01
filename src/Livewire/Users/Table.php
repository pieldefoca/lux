<?php

namespace Pieldefoca\Lux\Livewire\Users;

use Livewire\Attributes\Computed;
use Illuminate\Foundation\Auth\User;
use Pieldefoca\Lux\Livewire\LuxTable;
use Pieldefoca\Lux\Livewire\Table\Traits\WithBulkActions;
use Pieldefoca\Lux\Livewire\Table\Traits\Searchable;

class Table extends LuxTable
{
    use Searchable;
    use WithBulkActions;

    public $model = User::class;

    #[Computed]
    public function rowsQuery()
    {
        return User::query()
            ->when($this->filters['search'], function($query, $search) {
                return $query->where(function($query) use($search) {
                    return $query->where('username', 'like', "%{$search}%")
                        ->orWhere('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            });
    }

    public function render()
    {
        return view('lux::livewire.users.table');
    }
}
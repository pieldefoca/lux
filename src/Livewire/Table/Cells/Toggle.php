<?php

namespace Pieldefoca\Lux\Livewire\Table\Cells;

use Livewire\Component;
use Livewire\Attributes\Rule;
use Illuminate\Database\Eloquent\Model;
use Pieldefoca\Lux\Livewire\LuxComponent;

class Toggle extends LuxComponent
{
    public Model $model;

    public $field;

    #[Rule('required')]
    public $value;

    public $message;

    public function mount()
    {
        $this->value = $this->model->{$this->field};
    }

    public function render()
    {
        return view('lux::livewire.table.cells.toggle');
    }

    public function updatedValue()
    {
        $this->toggle();
    }

    public function toggle()
    {
        $this->model->update([
            $this->field => $this->value
        ]);

        $this->notifySuccess($this->message);
    }
}

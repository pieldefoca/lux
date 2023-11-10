<?php

namespace Pieldefoca\Lux\Livewire\Table\Traits;

trait Reorderable
{
    public function startReordering()
    {
        $this->reordering = true;

        $this->dispatch('refresh-drag');
    }

    public function finishReordering()
    {
        $this->reordering = false;
    }

    public function reorder($ids)
    {
        foreach($ids as $index => $id) {
            $this->model::find($id)->update(['order' => $index]);
        }
    }

}

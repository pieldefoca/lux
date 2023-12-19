<?php

namespace Pieldefoca\Lux\Livewire\Table\Traits;

trait WithBulkActions
{
    public $selectPage = false;
    public $selectAllRows = false;
    public $selected = [];

    public function renderingWithBulkActions()
    {
        // if ($this->selectAllRows) $this->selectPageRows();
    }

    public function updatedSelected()
    {
        $this->selectAllRows = false;
        $this->selectPage = false;
    }

    public function updatedSelectPage($value)
    {
        if ($value) return $this->selectPageRows();

        $this->selectAllRows = false;
        $this->selected = [];
    }

    public function selectPageRows()
    {
        $this->selected = $this->rows->pluck('id')->map(fn($id) => (string) $id)->toArray();
    }

    public function selectAll()
    {
        $this->selectPage = false;

        $this->selectAllRows = true;

        $this->selected = $this->rowsQuery->get()
            ->pluck('id')
            ->map(fn($id) => (string) $id)
            ->toArray();
    }

    public function unselectAll()
    {
        $this->selectAllRows = false;
        $this->selectPage = false;
        $this->selected = [];
    }

    public function clearSelection()
    {
        $this->unselectAll();
    }

    public function deleteSelected()
    {
        foreach((new $this->model)->whereIn('id', $this->selected)->get() as $model) {
            $model->delete();
        }

        $this->clearSelection();

        $this->notifySuccess('🤙🏾 Has eliminado las filas correctamente');
    }

    public function hasAnyRowSelected()
    {
        return count($this->selected) > 0;
    }

    public function areAllRowsSelected()
    {
        return $this->selectAllRows || (count($this->selected) === $this->rows->total());
    }

    public function getSelectedRowsQueryProperty()
    {
        return (clone $this->rowsQuery)
            ->unless($this->selectAllRows, fn($query) => $query->whereKey($this->selected));
    }
}

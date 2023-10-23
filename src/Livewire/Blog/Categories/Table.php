<?php

namespace Pieldefoca\Lux\Livewire\Blog\Categories;

use Pieldefoca\Lux\Models\Post;
use Livewire\Attributes\Computed;
use Pieldefoca\Lux\Enum\PostStatus;
use Pieldefoca\Lux\Livewire\LuxTable;
use Pieldefoca\Lux\Models\BlogCategory;
use Illuminate\Support\Facades\Pipeline;
use Pieldefoca\Lux\Livewire\Table\Traits\Searchable;
use Pieldefoca\Lux\Livewire\Table\Traits\WithFilters;
use Pieldefoca\Lux\Livewire\Table\Traits\WithSorting;
use Pieldefoca\Lux\Livewire\Table\Traits\WithBulkActions;

class Table extends LuxTable
{
    use WithBulkActions;
    use WithFilters;
    use Searchable;
    use WithSorting;

    public $model = BlogCategory::class;

    public $postStatusOptions = [];

    protected $listeners = [
        'blog-categories-updated' => '$refresh',
    ];

    public function mount()
    {
        $this->addFilter('active', '');
    }


    #[Computed]
    public function rows()
    {
        $query = BlogCategory::query()
            ->when($this->filters['search'], function($query, $search) {
                return $query->where('name', 'like', "%{$search}%");
            })
            ->when($this->filters['active'], function($query, $active) {
                return $query->where('active', $active);
            });
            
        return $this->applySorting($query)
            ->paginate();
    }

    public function activateSelected()
    {
        BlogCategory::whereIn('id', $this->selected)->update([
            'active' => true,
        ]);

        $this->clearSelection();

        $this->notifySuccess('🤙🏾 Has actualizado el estado de las categorías correctamente');
    }

    public function deactivateSelected()
    {
        BlogCategory::whereIn('id', $this->selected)->update([
            'active' => false,
        ]);

        $this->clearSelection();

        $this->notifySuccess('🤙🏾 Has actualizado el estado de las categorías correctamente');
    }

    public function render()
    {
        return view('lux::livewire.blog.categories.table');
    }
}
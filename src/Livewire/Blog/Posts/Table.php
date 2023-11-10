<?php

namespace Pieldefoca\Lux\Livewire\Blog\Posts;

use Pieldefoca\Lux\Models\Post;
use Livewire\Attributes\Computed;
use Pieldefoca\Lux\Enum\PostStatus;
use Pieldefoca\Lux\Livewire\LuxTable;
use Pieldefoca\Lux\Livewire\Table\Traits\Searchable;
use Pieldefoca\Lux\Livewire\Table\Traits\Reorderable;
use Pieldefoca\Lux\Livewire\Table\Traits\WithFilters;
use Pieldefoca\Lux\Livewire\Table\Traits\WithSorting;
use Pieldefoca\Lux\Livewire\Table\Traits\WithBulkActions;

class Table extends LuxTable
{
    use WithBulkActions;
    use WithFilters;
    use Searchable;
    use WithSorting;
    use Reorderable;

    public $model = Post::class;

    public $postStatusOptions = [];

    public function mount()
    {
        $this->addFilter('status', []);
        $this->addFilter('featured', '');

        foreach(PostStatus::cases() as $status) {
            $this->postStatusOptions[] = [
                'label' => $status->forHumans(),
                'value' => $status->value,
            ];
        }
    }

    #[Computed]
    public function rows()
    {
        $query = Post::query()
            ->when($this->filters['search'], function($query, $search) {
                return $query->where('title', 'like', "%{$search}%");
            })
            ->when($this->filters['status'], function($query, $status) {
                return $query->whereIn('status', $status);
            });
        return $this->applySorting($query)
            ->paginate();
    }

    public function render()
    {
        return view('lux::livewire.blog.posts.table');
    }
}

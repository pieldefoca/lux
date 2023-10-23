# Tablas

```html
<x-lux::table.table>
    <x-slot name="head">
        <x-lux::table.th>Título</x-lux::table.th>
        <x-lux::table.th>Estado</x-lux::table.th>
        <x-lux::table.th>Recomendado ⭐️</x-lux::table.th>
        <x-lux::table.th>Acciones</x-lux::table.th>
    </x-slot>

    <x-slot name="body">
        @foreach($this->rows as $post)
            <x-lux::table.tr :model="$post">
                <x-lux::table.td>{{ $post->title }}</x-lux::table.td>
                <x-lux::table.td>{{ $post->status->forHumans() }}</x-lux::table.td>
                <x-lux::table.td>
                    <livewire:table.cells.toggle 
                        :model="$post" 
                        field="featured" 
                        message="🤙 Has actualizado el estado del post correctamente"
                        key="featured-post-{{$post->id}}"
                    />
                </x-lux::table.td>
                <x-lux::table.td no-padding>
                    <a href="{{ route('lux.sliders.edit', $post) }}">
                        <x-lux::table.edit-button />
                    </a>
                </x-lux::table.td>
            </x-lux::table.tr>
        @endforeach
    </x-slot>
</x-lux::table.table>
```

```php
class Table extends LuxTable
{
    public $model = Post::class;

    #[Computed]
    public function rows()
    {
        return Post::query()->paginate();
    }

    public function render()
    {
        return view('lux::livewire.blog.posts.table');
    }
}
```

## Búsqueda

```php

use Livewire\Attributes\Computed;
use Pieldefoca\Lux\Livewire\Table\Traits\Searchable;
use Pieldefoca\Lux\Livewire\Table\Traits\WithFilters;

public function Table extends LuxTable
{
    use WithFilters;
    use Searchable;

    public $filters = ['search' => ''];

    #[Computed]
    public function rows()
    {
        return Post::query()
            ->when($this->filters['search'], function($query, $search) {
                return $query->where('title', 'like', "%{$search}%");
            })
            ->paginate();
    }
}
```

## Ordenación

```html
<x-lux::table.th sort="title">Título</x-lux::table.th>
```

```php
use Livewire\Attributes\Computed;
use Pieldefoca\Lux\Livewire\Table\Traits\WithSorting;

public function Table extends LuxTable
{
    use WithSorting;

    #[Computed]
    public function rows()
    {
        $query = Post::query();

        return $this->applySorting($query)
            ->paginate();
    }
}
```

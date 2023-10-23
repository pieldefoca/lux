# Select

Props:
* native (boolean = false): usar un input nativo o no 
* multiple (boolean = false): se pueden seleccionar varias opciones o no
* options: el nombre de la variable que contiene el array de opciones
* placeholder: texto para mostrar cuando no hay ninguna opción seleccionada

## Select nativo

```html
<x-lux::input.select wire:model="category" native>
    @foreach($options as $option)
        <option value="{{ $option->id }}">{{ $option->name }}</option>
    @endforeach
</x-lux::input.select>
```

## Select custom simple

```html
<x-lux::input.select 
    wire:model="category"
    options="categoryOptions"
/>
```

En el componente de livewire:

```php
...

public $category;
public $categoryOptions;

...

public function mount()
{
    ...

    $this->categoryOptions = [
        ['label' => 'Categoría 1', 'value' => 1],
        ['label' => 'Categoría 2', 'value' => 2],
        ['label' => 'Categoría 3', 'value' => 3],
    ];

    ...
}
```

## Select custom con imágenes

```html
<x-lux::input.select 
    wire:model="category"
    options="categoryOptions"
/>
```

En el componente de livewire:

```php
...

public $category;
public $categoryOptions;

...

public function mount()
{
    ...

    $this->categoryOptions = [
        ['label' => 'Categoría 1', 'value' => 1, 'image' => '/img/categoria-1.jpg'],
        ['label' => 'Categoría 2', 'value' => 2, 'image' => '/img/categoria-2.jpg'],
        ['label' => 'Categoría 3', 'value' => 3, 'image' => '/img/categoria-3.jpg'],
    ];

    ...
}
```

## Select múltiple

```html
<x-lux::input.select 
    multiple
    wire:model="categories"
    options="categoryOptions"
/>
```

En el componente de livewire:

```php
...

public $categories = [];
public $categoryOptions;

...

public function mount()
{
    ...

    $this->categoryOptions = [
        ['label' => 'Categoría 1', 'value' => 1, 'image' => '/img/categoria-1.jpg'],
        ['label' => 'Categoría 2', 'value' => 2, 'image' => '/img/categoria-2.jpg'],
        ['label' => 'Categoría 3', 'value' => 3, 'image' => '/img/categoria-3.jpg'],
    ];

    ...
}
```

## Traducible

Para hacer que el input sea traducible hay que marcar la propiedad del componente con el atributo #[Translatable]

```php
use Pieldefoca\Lux\Livewire\Attributes\Translatable;

#[Translatable]
public $category;
```

Si el select es múltiple hay que decirle que el valor vacío sea un array

```php
use Pieldefoca\Lux\Livewire\Attributes\Translatable;

#[Translatable([])]
public $categories = [];
```
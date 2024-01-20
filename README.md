- [Instalación](#instalacion)
- [Páginas](#páginas)
- [Formularios](#formularios)
    - [Campos](#campos)
        - [Texto](#texto)
- [Tablas](#tablas)
    - [Búsqueda](#búsqueda)
    - [Ordenación](#ordenación)
    - [Acciones en masa](#acciones-en-masa)
- [Modales](#modales)
- [Media Manager](#media-manager)
- [Traducciones](#traducciones)

# Páginas

Las páginas de la web van en la carpeta `resources/views/pages`.
Cada página tiene su correspondiente entrada en la tabla `lux_pages` de la base de datos.

## Crear página

Una página es realmente un componente de livewire que tiene como vista un fichero dentro de la carpeta `resources/views/pages`. Por tanto, para crear una página hay que crear un componente de livewire dentro de la carpeta `App\Livewire\Pages`.

```
php artisan make:livewire pages.home
```

Una vez creado habría que mover el fichero `resources/views/livewire/pages/home.blade.php` a `resources/views/pages/home.blade.php`.
En la clase del componente hay que cambiar la vista que devuelve el método render:

```php
public function render()
{
    return view('pages.home');
}
```

## Sincronizar las páginas con la base de datos

```
php artisan lux:pages
```

Este comando se encarga de crear una entrada en la tabla `lux_pages` para cada fichero que existe en la carpeta `resources/views/pages`.
Además de eso genera el fichero `routes/pages.php` con todas las rutas necesarias para acceder a cada página.

# Formularios

## Campos

### Texto

Traducible

```html
<x-input.text wire:model="name" translatable />
```

```php
use Pieldefoca\Lux\Livewire\Attributes\Translatable;

class Form
{
    #[Translatable]
    public $name;

    public function rules()
    {
        return [
            'name.*' => ['required'],
        ];
    }
}
```

# Tablas

Todas las tablas deben tener una propiedad `$model` y un método `rowsQuery`.

```php

namespace App\Livewire\Posts;

use Pieldefoca\Lux\Livewire\LuxComponent;
use Pieldefoca\Lux\Livewire\Table\Traits\LuxTable;
use use Livewire\Attributes\Computed;

class Table extends LuxComponent
{
    use LuxTable;

    public $model = Post::class;

    #[Computed]
    public function rowsQuery()
    {
        return Post::query();
    }

    public function render()
    {
        return view('livewire.posts.table');
    }
}
```

```html
<x-lux::table.table>
    <x-slot name="head">
        <x-lux::table.th sort="name">Nombre</x-lux::table.th>
        <x-lux::table.th sort="slug">URL</x-lux::table.th>
        <x-lux::table.th class="w-48">Estado</x-lux::table.th>
        <x-lux::table.th class="w-20"></x-lux::table.th>
    </x-slot>

    <x-slot name="body">
        @foreach($this->rows as $post)
            <livewire:posts.row :$post :$hasBulkActions :$reorderable :$locale :key="uniqid()" />
        @endforeach
    </x-slot>
</x-lux::table.table>
```

Cada fila de una tabla es un componente de livewire. Esto complica un poco la estructura pero permite poder realizar acciones en cada fila más fácilmente. Por ejemplo, se puede poner un select en una celda para cambiar el estado de una fila muy fácilmente.

A cada fila hay que pasarle 5 atributos: 1 es el modelo, 3 son heredados de la tabla y 1 es la clave que necesita livewire para identificar el componente.

```html
<livewire:posts.row :$post :$hasBulkActions :$reorderable :$locale :key="uniqid()" />
```

```html
<x-lux::table.tr :model="$tag">
    <x-lux::table.td>{{ $tag->translate('name', $locale) }}</x-lux::table.td>
    <x-lux::table.td>{{ $tag->translate('slug', $locale) }}</x-lux::table.td>
    <x-lux::table.td>
        <x-lux::input.toggle wire:model.live="active" />
    </x-lux::table.td>
    <x-lux::table.td>
        <x-lux::table.row-actions>
            <x-lux::menu.item @click="$dispatch('edit-blog-tag', { tag: {{ $tag->id }} })">
                <div class="flex items-center space-x-2">
                    <x-lux::tabler-icons.edit class="w-5 h-5" />
                    <span>Editar</span>
                </div>
            </x-lux::menu.item>
        </x-lux::table.row-actions>
    </x-lux::table.td>
</x-lux::table.tr>
```

```php
use Livewire\Component;
use Pieldefoca\Lux\Livewire\Table\Traits\LuxTableRow;
use Pieldefoca\Lux\Blog\Models\BlogTag;

class Row extends Component
{
    use LuxTableRow;

    public BlogTag $tag;

    public function render()
    {
        return view('lux-blog::livewire.tags.row');
    }
}
```

## Búsqueda

Para añadir un campo de búsqueda a la tabla hay que usar el trait `Pieldefoca\Lux\Livewire\Table\Traits\Searchable`.

```php
use Pieldefoca\Lux\Livewire\LuxComponent;
use Pieldefoca\Lux\Livewire\Table\Traits\LuxTable;
use Pieldefoca\Lux\Livewire\Table\Traits\Searchable;

class Table extends LuxComponent
{
    use LuxTable;
    use Searchable;
}
```

Este trait añade una propiedad `$search` a la tabla que será donde se guarde el texto de búsqueda que escriba el usuario.

Una vez añadido el trait hay que modificar el método `rowsQuery` para realizar la búsqueda.

```php
public function rowsQuery()
{
    return Post::query()
        ->when($this->search, function($query, $search) {
            //
        });
}
```

## Ordenación

Es posible ordenar las filas de la tabla en función del valor de una columna.

Hay que especificar qué columnas son las que tienen opción para ordenar añadiendo el atributo `sort` al th de la columna.

```html
<x-lux::table.table>
    <x-slot name="head">
        <x-lux::table.th sort="username">Nombre de usuario</x-lux::table.th>
        <!-- ... -->
    </x-slot>

    <x-slot name="body">
        <!-- ... -->
    </x-slot>
</x-lux::table.table>
```

## Acciones en masa

Para poder realizar acciones en masa hay que añadir el trait `Pieldefoca\Lux\Livewire\Table\Traits\WithBulkActions`.

```php
use Pieldefoca\Lux\Livewire\LuxComponent;
use Pieldefoca\Lux\Livewire\Table\Traits\WithBulkActions;
use Pieldefoca\Lux\Livewire\Table\Traits\LuxTable;

class Table extends LuxComponent
{
    use LuxTable;
    use WithBulkActions;
}
```

Esto añadirá un checkbox a cada fila de la tabla y un checkbox en la cabecera para poder seleccionar todas las filas a la vez.

### Añadir acciones en masa

El componente `table` tiene un slot llamado `bulkActions` en el que se pueden añadir acciones para ejecutar en masa.

Ese slot es un menú al que hay que añadir items de esta forma:

```html
<x-slot:bulkActions>
    <x-lux::menu.item wire:click="activateSelected">Activar seleccionadas</x-lux::menu.item>
    <x-lux::menu.item wire:click="deactivateSelected">Desactivar seleccionadas</x-lux::menu.item>
</x-slot>
```

Después en la tabla habrá que implementar los métodos que correspondan para ejecutar las acciones. En el ejemplo anterior habría que implementar el método `activateSelected` para activar las filas seleccionadas y el método `deactivateSelected` para desactivar las filas seleccionadas.

### Eliminación en masa

Por defecto, cuando se añaden las acciones en masa, se podrán eliminar varias filas de la tabla al mismo tiempo. Para desactivar esta opción hay que llamar al método `disableBulkDeletion` desde el método `mount` de la tabla:

```php
public function mount()
{
    $this->disableBulkDeletion();
}
```

## Filtros

Para poder filtrar las filas de la tabla hay que añadir una propiedad `$filters`.

```php
use Pieldefoca\Lux\Livewire\LuxComponent;
use Pieldefoca\Lux\Livewire\Table\Traits\WithBulkActions;
use Pieldefoca\Lux\Livewire\Table\Traits\LuxTable;

class Table extends LuxComponent
{
    use LuxTable;

    public $filters = [
        'status' => null,
    ];
}
```

En el método rowsQuery de la tabla habrá que añadir un when para realizar el filtro;

```php
public function rowsQuery()
{
    return Post::query()
        ->when($this->filters['status'], function($query, $status) {
            //
        });
}
```

Para añadir los filtros a la vista de la tabla existe el slot `filters`, un popover al que habrá que añadir los inputs que sean necesarios:

```html
<x-slot:filters>
    <div>
        <x-lux::input.group label="Estado">
            <x-lux::input.select native wire:model.live="filters.status">
                <option value="">Cualquiera</option>
                <option value="published">Publicado</option>
                <option value="draft">Borrador</option>
            </x-lux::input.select>
        </x-lux::input.group>
    </div>

    <div>
        <x-lux::input.group label="Creado por">
            <x-lux::input.select native wire:model.live="filters.author">
                <option value="">Cualquiera</option>
                <!-- -->
            </x-lux::input.select>
        </x-lux::input.group>
    </div>
</x-slot:filters>
```

# Modales

# Media Manager

Todos los archivos que suba el usuario se guardan en `public/uploads`.

Al subir una imagen se guarda la original en `public/uploads/._ogs` y se crean varias versiones:
1. 1920px de ancho. El nombre no lleva ningún sufijo y es la imagen que se usa por defecto.
2. 250px de ancho. Lleva el sufijo `-thumb`.

# Instalación

1. Crear un fichero `auth.json` en la raíz de la aplicación
```json
{
    "github-oauth": {
        "github.com": "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"
    }
}
```

2. Añadir el repositorio en `composer.json`
```json
"repositories": [
     {
         "type": "vcs",
         "url": "https://github.com/pieldefoca/lux"
     }
],
```

3. Instalar el paquete
```shell
composer require pieldefoca/lux
```

4. Ejecutar las migraciones
```shell
php artisan migrate
```

5. Instalar tailwind
[https://tailwindcss.com/docs/guides/laravel]

6. Crear el fichero `resources/css/lux.css` y añadir el import:
```css
@import "../../vendor/pieldefoca/lux/resources/css/lux.css";
```

7. Añadir los inputs en `vite.config.js`:
```js
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/css/lux.css',
                'resources/js/lux.js',
            ],
            refresh: true,
        }),
    ],
```

8. Publicar el fichero de configuración y los assets

```shell
php artisan vendor:publish --tag=lux-config --tag=lux-assets
```

9. Compilar los assets

```shell
npm run dev
```

10. Siguientes pasos:
* Crear un usuario
* Definir el logo

# Crear un usuario

```shell
php artisan lux:user
```

# Logo

Definir la url del logo en `config/lux.php`

```php
'logo' => asset('img/logo.svg'),
```

# Traducciones

Para traducir textos se pueden utilizar las mismas herramientas de laravel (trans, @lang, __).
Para la clave de cada texto es mejor utilizar una clave genérica que no tenga nada que ver con el texto que representa. Esto tiene sus desventajas a la hora de identificar el texto pero es mejor para que todas las claves sean coherentes en el futuro y no haya que cambiarlas cuando cambia el texto para que no lleve a confusión o que unas estén en un idioma y otras en otro.
Como norma general, el formato sería home-101, contacto-101 (nombre de la página + guión + número de 3 cifras empezando por el 101).
En caso de cambiar el nombre del fichero de la vista de una página habría que cambiar también el nombre del fichero de traducciones y las claves.
```
// Evitar claves relacionadas con el texto
{{ trans('home.trabajamos-con-estas-marcas') }}

// Usar claves genéricas
{{ trans('home.home-101') }}
```

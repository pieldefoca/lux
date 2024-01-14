- [Instalación](#instalacion)
- [Páginas](#páginas)
- [Formularios](#formularios)
    - [Campos](#campos)
        - [Texto](#texto)
- [Tablas](#tablas)
    - [Búsqueda](#búsqueda)
    - [Ordenación](#ordenación)
    - [Acciones en masa](#acciones-en-masa)
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

# Tablas

Una tabla es un componente de livewire que extiende de `LuxTable`.
Todas las tablas deben tener una propiedad `$model` y un método `rowsQuery`.

```php
use Pieldefoca\Lux\Livewire\LuxTable;
use use Livewire\Attributes\Computed;

class Table extends LuxTable
{
    public $model = Model::class;

    #[Computed]
    public function rowsQuery()
    {
        //
    }
}
```

```html
<x-lux::table.table>
    <x-slot name="head">
        <x-lux::table.th sort="username">Nombre de usuario</x-lux::table.th>
        <x-lux::table.th sort="name">Nombre</x-lux::table.th>
        <x-lux::table.th sort="email">Email</x-lux::table.th>
        <x-lux::table.th>Acciones</x-lux::table.th>
    </x-slot>

    <x-slot name="body">
        @foreach($this->rows as $user)
            <x-lux::table.tr :model="$user">
                <x-lux::table.td>{{ $user->username }}</x-lux::table.td>
                <x-lux::table.td>{{ $user->name }}</x-lux::table.td>
                <x-lux::table.td>{{ $user->email }}</x-lux::table.td>
                <x-lux::table.td no-padding>
                    <div class="space-x-3">
                        <a href="{{ route('lux.users.edit', $user) }}">
                            <x-lux::table.edit-button />
                        </a>
                        <x-lux::table.delete-button />
                    </div>
                </x-lux::table.td>
            </x-lux::table.tr>
        @endforeach
    </x-slot>
</x-lux::table.table>
```

## Búsqueda

Para añadir un campo de búsqueda a la tabla hay que usar el trait `Pieldefoca\Lux\Livewire\Table\Traits\Searchable`.

```php
use Pieldefoca\Lux\Livewire\Table\Traits\Searchable;
use Pieldefoca\Lux\Livewire\LuxTable;

class Table extends LuxTable
{
    use Searchable;
}
```

Una vez añadido el trait hay que modificar el método `rowsQuery` para realizar la búsqueda.
La búsqueda en realidad es un filtro, por lo que hay que comprobar que exista algo en `$this->filters['search']`.

```php
public function rowsQuery()
{
    return Post::query()
        ->when($this->filters['search'], function($query, $search) {
            //
        });
}
```

## Ordenación

Es posible ordenar las filas de la tabla en función del valor de una columna.
Para ello hay que añadir el trait `Pieldefoca\Lux\Livewire\Table\Traits\WithSorting` a la tabla.

```php
use Pieldefoca\Lux\Livewire\Table\Traits\WithSorting;
use Pieldefoca\Lux\Livewire\LuxTable;

class Table extends LuxTable
{
    use WithSorting;
}
```

Una vez añadido el trait hay que especificar qué columnas son las que tienen opción para ordenar añadiendo el atributo `sort` al th de la columna.

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
use Pieldefoca\Lux\Livewire\Table\Traits\WithBulkActions;
use Pieldefoca\Lux\Livewire\LuxTable;

class Table extends LuxTable
{
    use WithBulkActions;
}
```

Esto añadirá un checkbox a cada fila de la tabla y un checkbox en la cabecera para poder seleccionar todas las filas a la vez.

### Añadir acciones en masa

El componente `table` tiene un slot llamado `bulkActions` en el que se pueden añadir acciones para ejecutar en masa.

Ese slot es un dropdown al que hay que añadir items de esta forma:

```html
<x-slot name="bulkActions">
    <x-lux::dropdown.item wire:click="activateSelected">Activar seleccionadas</x-lux::dropdown.item>
    <x-lux::dropdown.item wire:click="deactivateSelected">Desactivar seleccionadas</x-lux::dropdown.item>
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

- [Instalación](#instalacion)
- [Traducciones](#traducciones)

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

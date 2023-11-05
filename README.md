# Instalación

```shell
composer require pieldefoca/lux
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
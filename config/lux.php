<?php

use Pieldefoca\Lux\Support\SidebarItem;

return [
	'prefix' => 'admin',

    'logo' => '/img/fire.svg',

    'login_image' => '/img/firulais.webp',

    'sliders' => [
        'fields' => ['title', 'subtitle', 'action']
    ],

    'locales' => [
        'es', 'eu',
    ],

    'fallback_locale' => 'es',

    'node_binary' => env('NODE_BINARY'),

    'npm_binary' => env('NPM_BINARY'),
];

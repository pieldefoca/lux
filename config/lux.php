<?php

use Pieldefoca\Lux\Support\SidebarItem;

return [
	'prefix' => 'admin',

    'logo' => '/img/fire.svg',

    'sidebar' => [
        (new SidebarItem())->withLabel('Inicio')
            ->withTablerIcon('home-2')
            ->withRouteName('lux.dashboard')
            ->activeOn('lux.dashboard'),
        (new SidebarItem())->withLabel('Multimedia')
            ->withTablerIcon('photo-video')
            ->withRouteName('lux.media.index')
            ->activeOn('lux.media.*'),
    ],

    'sliders' => [
        'fields' => ['title', 'subtitle', 'action']
    ],
];

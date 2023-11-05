<?php

use Pieldefoca\Lux\Support\SidebarItem;

return [
	'prefix' => 'admin',

    'logo' => asset('img/fire.svg'),

    'sidebar' => [
        (new SidebarItem())->withLabel('Inicio')
            ->withTablerIcon('home-2')
            ->withRouteName('lux.dashboard')
            ->activeAt('lux.dashboard'),
    ],
];

<?php

namespace Pieldefoca\Lux\Support;

use Illuminate\Support\Facades\Route;
use Pieldefoca\Lux\Support\SidebarGroup;

abstract class SidebarElement
{
    public $label = '';
    public $tablerIcon = '';
    public $activeAt = [];

    public function withLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    public function withTablerIcon($tablerIcon)
    {
        $this->tablerIcon = $tablerIcon;

        return $this;
    }

    public function activeAt($routes)
    {
        $this->activeAt = $routes;

        return $this;
    }

    public function isGroup()
    {
        return $this instanceof SidebarGroup;
    }

    public function isActive()
    {
        return Route::is(str($this->activeAt)->explode(',')->map(fn($route) => trim($route))->toArray());
    }
}
<?php

namespace Pieldefoca\Lux\Support;

use Illuminate\Support\Facades\Route;
use Pieldefoca\Lux\Support\SidebarGroup;

abstract class SidebarElement
{
    public $label = '';
    public $tablerIcon = '';
    public $activeOn = [];
    public $alwaysActive = false;
    public $roles = [];

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

    public function activeOn($routes)
    {
        $this->activeOn = $routes;

        return $this;
    }

    public function forRoles($roles)
    {
        if(is_array($roles)) $this->roles = $roles;

        if(is_string($roles)) {
            $roles = array_map(fn($role) => trim($role), explode(',', $roles));

            $this->roles = $roles;
        }

        return $this;
    }

    public function isGroup()
    {
        return $this instanceof SidebarGroup;
    }

    public function isActive()
    {
        if($this->alwaysActive) return true;

        return Route::is(str($this->activeOn)->explode(',')->map(fn($route) => trim($route))->toArray());
    }

    public function shouldShow()
    {
        if(empty($this->roles)) return true;
        
        return auth()->user()->hasRole($this->roles);
    }
}
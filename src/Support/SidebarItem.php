<?php

namespace Pieldefoca\Lux\Support;

class SidebarItem extends SidebarElement
{
    public $link;
    public $routeName;

    public function withLink($link)
    {
        $this->link = $link;

        return $this;
    }

    public function withRouteName($routeName)
    {
        $this->routeName = $routeName;

        return $this;
    }
}
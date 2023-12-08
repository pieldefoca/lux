<?php

namespace Pieldefoca\Lux\Support;

class SidebarGroup extends SidebarElement
{
    public array $items = [];

    public function keepAlwaysActive()
    {
        $this->alwaysActive = true;

        return $this;
    }

    public function withItems(array $items)
    {
        $this->items = $items;

        return $this;
    }
}
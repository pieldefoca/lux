<?php

namespace Pieldefoca\Lux\Support;

class SidebarGroup extends SidebarElement
{
    public array $items = [];

    public function withItems(array $items)
    {
        $this->items = $items;

        return $this;
    }
}
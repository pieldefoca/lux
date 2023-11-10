<?php

namespace Pieldefoca\Lux\Livewire\MediaManager;

use Livewire\Attributes\On;
use Pieldefoca\Lux\Livewire\LuxComponent;
use Pieldefoca\Lux\Models\Media;

class InfoSidebar extends LuxComponent
{
    public Media $media;

    public $visible = false;

    #[On('edit-media')]
    public function open(Media $media)
    {
        $this->media = $media;
        
        $this->visible = true;
    }

    public function render()
    {
        return view('lux::livewire.media-manager.info-sidebar');
    }
}

<?php

namespace Pieldefoca\Lux\Livewire\MediaManager;

use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Pieldefoca\Lux\Models\Media;
use Livewire\Attributes\Computed;
use Pieldefoca\Lux\Livewire\LuxComponent;
use Pieldefoca\Lux\Livewire\Attributes\Translatable;

class Slideover extends LuxComponent
{
    public Media $media;

    public $visible = false;

    #[Translatable]
    public $name;

    #[Translatable]
    public $alt;

    #[Translatable]
    public $title;

    #[On('edit-media')]
    public function open(Media $media)
    {
        $this->media = $media;

        $this->name = $media->getTranslations('name');
        $this->alt = $media->getTranslations('alt');
        $this->title = $media->getTranslations('title');
        
        $this->visible = true;
    }

    #[Computed]
    public function url()
    {
        if(!isset($this->media)) return null;
        
        return config('app.url') . '/uploads/' . Str::slug($this->name[$this->currentLocaleCode]) . '.' . $this->media?->getExtension();
    }

    public function save()
    {
        // $this->validate();

        $this->media->update([
            'name' => $this->name,
            'alt' => $this->alt,
            'title' => $this->title,
        ]);

        $this->visible = false;
    }

    public function deleteMedia()
    {
        $this->media->delete();
    }

    public function render()
    {
        return view('lux::livewire.media-manager.slideover');
    }
}

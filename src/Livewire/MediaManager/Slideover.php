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

    public $filename;

    #[Translatable]
    public $alt;

    #[Translatable]
    public $title;

    public $deletable = false;

    public function updatedFilename($value)
    {
        $this->filename = str($value)->slug()->toString();
    }

    #[On('edit-media')]
    public function open(Media $media)
    {
        $this->media = $media;

        $this->filename = $media->filename;
        $this->alt = $media->getTranslations('alt');
        $this->title = $media->getTranslations('title');
        
        $this->visible = true;
    }

    #[Computed]
    public function url()
    {
        if(!isset($this->media)) return null;
        
        return config('app.url') . '/uploads/' . Str::slug($this->media->filename) . '.' . $this->media?->extension;
    }

    public function save()
    {
        $this->validate();

        $this->media->rename($this->filename);

        $this->media->update([
            'filename' => $this->filename,
            'alt' => $this->alt,
            'title' => $this->title,
        ]);

        $this->visible = false;
    }

    public function deleteMedia()
    {
        $this->media->delete();
    }

    public function rules()
    {
        return [
            'filename' => ['required'],
        ];
    }

    public function messages()
    {
        return [
            'filename.required' => 'Escribe un nombre',
        ];
    }

    public function render()
    {
        return view('lux::livewire.media-manager.slideover');
    }
}

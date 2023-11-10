<?php

namespace Pieldefoca\Lux\Livewire\MediaManager;

use Illuminate\Support\Str;
use Livewire\WithFileUploads;
use Pieldefoca\Lux\Models\Media;
use Livewire\Attributes\Computed;
use Pieldefoca\Lux\Livewire\LuxComponent;

class Index extends LuxComponent
{
    use WithFileUploads;

    public $uploads;

    public $selected = [];

    public $filters = [
        'type' => 'all',
    ];

    public function updatedUploads($value)
    {
        foreach($value as $file) {
            $name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $filename = Str::slug($name) . '.' . $file->getClientOriginalExtension();
            $file->storeAs('/', $filename, 'uploads');
            Media::create([
                'name' => $name,
                'filename' => $filename,
            ]);
        }
    }

    #[Computed]
    public function onlyOneSelected()
    {
        return count($this->selected) === 1;
    }

    public function select(Media $media)
    {
        $index = array_search($media->id, $this->selected);

        if($index === false) {
            $this->selected[] = $media->id;
        } else {
            unset($this->selected[$index]);
        }
    }

    public function render()
    {
        return view('lux::livewire.media-manager.index');
    }
}

<?php

namespace Pieldefoca\Lux\Livewire\MediaManager;

use Spatie\Image\Image;
use Illuminate\Support\Str;
use Spatie\Image\Enums\Fit;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Pieldefoca\Lux\Models\Media;
use Livewire\Attributes\Computed;
use Pieldefoca\Lux\Enum\MediaType;
use Pieldefoca\Lux\Facades\MediaManager;
use Pieldefoca\Lux\Livewire\LuxComponent;

class Index extends LuxComponent
{
    use WithFileUploads;
    use WithPagination;

    public $view;

    public $perPage = 10;

    public $uploads;

    public $selected = [];

    public $type;

    public $search = '';

    public function mount()
    {
        $this->view = session()->get('lux-media-manager-view', 'list');
    }

    public function updatedUploads($files)
    {
        MediaManager::save($files);
    }

    #[Computed]
    public function mediaItems()
    {
        return Media::query()
            ->where('filename', '!=', '_placeholder.jpg')
            ->when($this->type, function($query, $type) {
                if($type === MediaType::File->value) {
                    return $query->where(function($query) {
                        return $query->where('media_type', MediaType::Pdf->value)
                            ->orWhere('media_type', MediaType::File->value);
                    });
                }

                return $query->where('media_type', $type);
            })
            ->when($this->search, function($query, $search) {
                return $query->where(function($query) use($search) {
                    return $query->where('name', 'like', "%{$search}%")
                        ->orWhere('filename', 'like', "%{$search}%");
                });
            })
            ->paginate($this->perPage);
    }

    #[Computed]
    public function onlyOneSelected()
    {
        return count($this->selected) === 1;
    }

    public function deleteMedia(Media $media)
    {
        $media->delete();
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

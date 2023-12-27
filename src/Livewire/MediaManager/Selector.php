<?php

namespace Pieldefoca\Lux\Livewire\MediaManager;

use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Pieldefoca\Lux\Models\Media;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Modelable;
use Pieldefoca\Lux\Enum\MediaType;
use Pieldefoca\Lux\Livewire\LuxModal;
use Pieldefoca\Lux\Livewire\Attributes\Translatable;

class Selector extends LuxModal
{
    use WithFileUploads;

    public Media $media;

    public $field;

    public bool $swapping = false;

    #[Modelable]
    public $selected = [];

    public $uploads;

    public $multiple;

    public $type;

    public $search = '';

    public $page = 1;

    public function updatedUploads($value)
    {
        foreach($value as $file) {
            $name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $filename = Str::slug($name) . '.' . $file->getClientOriginalExtension();
            $file->storeAs('/', $filename, 'uploads');
            $media = Media::create([
                'name' => $name,
                'filename' => $filename,
                'mime_type' => $file->getMimeType(),
            ]);
            $this->select($media);
        }
    }

    #[On('select-media')]
    public function selectMedia($field, $preSelected, $multiple = true)
    {
        $this->swapping = false;

        $this->field = $field;

        $this->selected = $preSelected;
        
        $this->multiple = $multiple;

        $this->show();
    }

    #[On('swap-media')]
    public function swapMedia(int $selectedId)
    {
        $this->swapping = true;

        $this->selected = [$selectedId];

        $this->multiple = false;

        $this->show();
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
            ->take(20 * $this->page)
            ->get();
    }

    #[Computed]
    public function canLoadMore()
    {
        return $this->mediaItems->count() < Media::count();
    }

    public function loadMore()
    {
        $this->page++;
    }

    public function select(Media $media)
    {
        if($this->multiple) {
            $index = array_search($media->id, $this->selected);
    
            if($index === false) {
                $this->selected[] = $media->id;
            } else {
                unset($this->selected[$index]);
            }
        } else {
            $this->selected[0] = $media->id;
        }

        $this->selected = array_values($this->selected);
    }

    public function confirmSelection()
    {
        if($this->swapping) {
            $this->dispatch('media-swapped', mediaId: $this->selected[0]);
        } else {
            $this->dispatch('media-selected', field: $this->field, mediaIds: $this->selected);
        }

        $this->hide();
    }

    public function isSelected($id)
    {
        return in_array($id, $this->selected);
    }

    public function render()
    {
        return view('lux::livewire.media-manager.selector');
    }
}

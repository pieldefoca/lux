<?php

namespace Pieldefoca\Lux\Livewire;

use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\WithFileUploads;
use Pieldefoca\Lux\Models\Page;
use Pieldefoca\Lux\Models\Media;

class MediaSelector extends Component
{
    use WithFileUploads;

    public Page $page;

    public $file;

    public $key;

    public function updatedFile($value)
    {
        $name = pathinfo($value->getClientOriginalName(), PATHINFO_FILENAME);
        $filename = Str::slug($name) . '.' . $value->getClientOriginalExtension();
        $value->storeAs('/', $filename, 'uploads');
        $media = Media::create([
            'name' => $name,
            'filename' => $filename,
            'mime_type' => $value->getMimeType(),
        ]);
        $this->page->addMedia([app()->currentLocale() => [$media->id]], 'media', $this->key);
    }

    public function render()
    {
        return view('lux::livewire.media-selector');
    }
}

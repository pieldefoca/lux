<?php

namespace Pieldefoca\Lux\Models;

use Spatie\Image\Image;
use Spatie\Image\Enums\Fit;
use Pieldefoca\Lux\Enum\MediaType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Media extends Model
{
    use HasFactory;
    use HasTranslations;

    protected $table = 'lux_media';

    protected $guarded = [];

    protected $casts = [
        'media_type' => MediaType::class,
    ];

    public $translatable = ['name', 'alt', 'title'];

    public function mimeType(): Attribute
    {
        return Attribute::make(
            set: function($value) {
                return [
                    'mime_type' => $value,
                    'media_type' => MediaType::fromMimeType($value)->value,
                ];
            }
        );
    }

    public function size(): Attribute
    {
        return Attribute::make(
            get: function() {
                return Storage::disk('uploads')->size("{$this->filename}.{$this->extension}");
            }
        );
    }

    public function getUrl()
    {
        return Storage::disk('uploads')->url($this->filename);
    }

    public function getThumbUrl()
    {
        return Storage::disk('uploads')->url("{$this->filename}-thumb.webp");
    }

    public function getPath()
    {
        return Storage::disk('uploads')->path($this->filename);
    }

    public function getExtension()
    {
        return pathinfo($this->filename, PATHINFO_EXTENSION);
    }

    public function isImage()
    {
        return str($this->mime_type)->startsWith('image');
    }

    public function isVideo()
    {
        return str($this->mime_type)->startsWith('video');
    }

    public function isFile()
    {
        return !$this->isImage() && !$this->isVideo();
    }

    public function isPdf()
    {
        return str($this->mime_type)->contains('pdf');
    }

    public function createVariations()
    {
        if(! $this->isImage()) return $this->copyOriginal();

        $originalPath = $this->getOriginalPath();
        $extension = pathinfo($originalPath)['extension'];
        $extension = ($extension === 'jpg' || $extension === 'jpeg')
            ? 'webp'
            : $extension;
        
        Image::load($originalPath)
            ->fit(Fit::Contain, 1920, 1080)
            ->save(Storage::disk('uploads')->path("{$this->filename}.{$extension}"));
        // Image::load($originalPath)
        //     ->fit(Fit::Contain, 1280, 720)
        //     ->save(Storage::disk('uploads')->path("{$this->filename}-md.{$extension}"));
        // Image::load($originalPath)
        //     ->fit(Fit::Contain, 640, 360)
        //     ->save(Storage::disk('uploads')->path("{$this->filename}-sm.{$extension}"));
        Image::load($originalPath)
            ->fit(Fit::Contain, 250, 180)
            ->save(Storage::disk('uploads')->path("{$this->filename}-thumb.{$extension}"));

        $this->update(['extension' => $extension]);
    }

    public function copyOriginal()
    {
        return copy(
            $this->getOriginalPath(), 
            Storage::disk('uploads')->path("{$this->filename}.{$this->extension}")
        );
    }

    public function getOriginalPath()
    {
        return Storage::disk('uploads')->path("._ogs/{$this->original_image}");
    }

    public function deleteAll()
    {
        $this->deleteOriginal();

        $this->deleteVariations();
    }

    protected function deleteOriginal()
    {
        Storage::disk('uploads')->delete("._ogs/{$this->original_image}");
    }

    protected function deleteVariations()
    {

    }
}

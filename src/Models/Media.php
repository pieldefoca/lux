<?php

namespace Pieldefoca\Lux\Models;

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

    public $translatable = ['name', 'filename', 'alt', 'title'];

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

    public function getUrl()
    {
        return Storage::disk('uploads')->url($this->filename);
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
}

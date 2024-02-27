<?php

namespace Pieldefoca\Lux\Models;

use File;
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
                return Storage::disk('uploads')->size("{$this->id}/{$this->filename}.{$this->extension}");
            }
        );
    }

    public function fullName(): Attribute
    {
        return Attribute::make(
            get: function() {
                return $this->filename . '.' . $this->extension;
            }
        );
    }

    public function getUrl()
    {
        return Storage::disk('uploads')->url("{$this->id}/{$this->filename}.{$this->extension}");
    }

    public function getThumbUrl()
    {
        return Storage::disk('uploads')->url("{$this->id}/{$this->filename}-thumb.{$this->extension}");
    }

    public function getPath()
    {
        return Storage::disk('uploads')->path($this->id);
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
        if(! $this->isImage()) return;

        $convertibleExtensions = ['jpg', 'jpeg'];
        $convertible = in_array($this->extension, $convertibleExtensions);
        $newExtension = $convertible ? 'webp' : $this->extension;
        $originalPath = public_path("uploads/{$this->id}/{$this->filename}.{$this->extension}");

        // Optimize the original image
        Image::load($originalPath)
            ->optimize()
            ->save(Storage::disk('uploads')->path("{$this->id}/{$this->filename}.{$this->extension}"));

        if($convertible) {
            // Convert the image to the new format
            Image::load($originalPath)
                ->save(Storage::disk('uploads')->path("{$this->id}/{$this->filename}.{$newExtension}"));
        }

        // Create the variations
        Image::load($originalPath)
            ->fit(Fit::Contain, 1920, 1080)
            ->save(Storage::disk('uploads')->path("{$this->id}/{$this->filename}.{$newExtension}"));
        // Image::load($originalPath)
        //     ->fit(Fit::Contain, 1280, 720)
        //     ->save(Storage::disk('uploads')->path("{$this->filename}-md.{$newExtension}"));
        // Image::load($originalPath)
        //     ->fit(Fit::Contain, 640, 360)
        //     ->save(Storage::disk('uploads')->path("{$this->filename}-sm.{$newExtension}"));
        Image::load($originalPath)
            ->fit(Fit::Contain, 350, 200)
            ->save(Storage::disk('uploads')->path("{$this->id}/{$this->filename}-thumb.{$newExtension}"));

        if($convertible) {
            // Delete the original image
            Storage::disk('uploads')->delete("{$this->id}/{$this->filename}.{$this->extension}");
        }

        $this->update(['extension' => $newExtension]);
    }

    public function rename($newName)
    {
        $suffixes = ['', '-thumb'];
        $previousFilename = $this->filename;

        foreach($suffixes as $suffix) {
            $filename = "{$previousFilename}{$suffix}.{$this->extension}";
            $newFilename = "{$newName}{$suffix}.{$this->extension}";
            $path = public_path("uploads/{$this->id}/{$filename}");
            $newPath = public_path("uploads/{$this->id}/{$newFilename}");

            File::move($path, $newPath);
        }
    }

    public function deleteAll()
    {
        Storage::disk('uploads')->deleteDirectory($this->id);
    }
}

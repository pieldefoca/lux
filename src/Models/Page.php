<?php

namespace Pieldefoca\Lux\Models;

use Livewire\Livewire;
use Illuminate\Support\Str;
use Pieldefoca\Lux\Facades\Pages;
use Pieldefoca\Lux\Enum\MediaType;
use Pieldefoca\Lux\Traits\HasMedia;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Page extends Model
{
    use HasFactory;
    use HasTranslations;
    use HasMedia;

    protected $table = 'lux_pages';

    protected $guarded = [];

    protected $casts = [
        'is_home_page' => 'boolean',
        'visible' => 'boolean',
        'dynamic_page' => 'boolean',
    ];

    public $translatable = ['slug', 'slug_prefix', 'title', 'description'];

    public function scopePublished($query)
    {
        return $query->where('visible', true);
    }

    public function check()
    {
        $viewWithSlashes = str($this->view)->replace('.', '/')->toString();
        if(!File::exists(resource_path('views/pages/' . $viewWithSlashes . '.blade.php'))) {
            return false;
        }
        return true;
    }

    public function localizedRoute($locale = null)
    {
        if(is_null($locale)) $locale = app()->currentLocale();

        if(Locale::default()->code === $locale) {
            return '/' . $this->translate('slug', $locale);
        } else {
            return '/' . $locale . '/' .$this->translate('slug', $locale);
        }

    }

    public function langFilePath(): Attribute
    {
        return Attribute::make(
            get: fn() => str($this->key)->replace('.', '/')->toString() . '.php',
        );
    }

    public function getLangFileRelativePath(): string
    {
        $splits = explode('.', $this->view);

        if(count($splits) > 1) {
            $folders = array_slice($splits, 0, count($splits) - 1);

            return str(implode('/', $folders))->trim('/')->toString();
        }
        return '';
    }

    public function langFileKey(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->view,
        );
    }

    public function getLangFilename(): string
    {
        $splits = explode('.', $this->view);

        if(count($splits) > 1) return end($splits);

        return $this->view;
    }

    public function hasLangFileFor($locale)
    {
        return File::exists(lang_path($locale . '/' . $this->getLangFileRelativePath() . '/' . $this->getLangFilename() . '.php'));
    }

    public function createLangFile(string $locale)
    {
        $this->createLangFilePath($locale);

        $defaultLocale = Locale::default()->code;

        if($this->hasLangFileFor($defaultLocale)) {
            $from = lang_path($defaultLocale . '/' . $this->getLangFileRelativePath() . '/' . $this->getLangFilename() . '.php');
            $to = lang_path($locale . '/' . $this->getLangFileRelativePath() . '/' . $this->getLangFilename() . '.php');
            copy($from, $to);
        } else {
            $langFilePathname = lang_path($defaultLocale . '/' . $this->getLangFileRelativePath() . '/' . $this->getLangFilename() . '.php');
            if(!File::exists($langFilePathname)) {
                file_put_contents($langFilePathname, "<?php\n\nreturn [\n\t\n];");
            }
        }
    }

    protected function createLangFilePath(string $locale)
    {
        $langFilePath = lang_path($locale . '/' . $this->getLangFileRelativePath());
        try {
            mkdir($langFilePath, 0777, true);
        } catch(\Exception $e) {};
    }

    public function imageUrl(string $key)
    {
        $media = $this->getFirstMedia('media', key: $key);

        return is_null($media) ? "lux-image:{$key}" : $media->getUrl();
    }

    public function livewireComponentClass()
    {
        $splits = explode('.', $this->view);
        $component = str($this->view)->title()->replace('-', '')->toString();
        if(count($splits) > 1) {
            $componentName = str(array_pop($splits))->title()->replace('-', '')->toString();
            $componentRelativePath = str(implode('\\', $splits))->title()->replace('-', '')->toString();
            $component = $componentRelativePath . '\\' . $componentName;
        }
        return "App\Livewire\Pages\\{$component}::class";
    }

    public function isHidden(): bool
    {
        return !$this->attributes['visible'];
    }

    public function isDynamic(): bool
    {
        return $this->dynamic_page;
    }

    public function isLegalPage(): bool
    {
        $legalPages = ['accesibilidad', 'politica-privacidad', 'politica-cookies', 'aviso-legal'];
        
        return in_array($this->key, $legalPages);
    }

    public function toggleVisibility()
    {
        $this->update(['visible' => !$this->attributes['visible']]);

        Pages::generatePageRoutes();
    }

    public function getImages($locale = null)
    {
        return $this->getMedia(collection: 'media', locale: $locale, mediaType: MediaType::Image->value);
    }

    public function getVideos($locale = null)
    {
        return $this->getMedia(collection: 'media', locale: $locale, mediaType: MediaType::Video->value);
    }

    public function getFiles($locale = null)
    {
        return $this->getMedia(collection: 'media', locale: $locale, mediaType: MediaType::File->value);
    }

    public function getImageOrCreate($key)
    {
        $media = $this->getMedia('media', locale: app()->currentLocale(), key: $key);

        if($media->isEmpty()) {
            $ids = [];

            foreach(Locale::all() as $locale) {
                $ids[$locale->code] = [1];
            }

            $this->addMedia($ids)
                ->saveTranslations()
                ->withKey($key)
                ->toCollection('media');

            return $this->getMedia('media', locale: app()->currentLocale(), key: $key)->first();
        }

        return $media->first();
    }

    public function registerMediaCollections()
    {
        $this->addMediaCollection('media');
    }
}

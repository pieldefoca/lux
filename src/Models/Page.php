<?php

namespace Pieldefoca\Lux\Models;

use Illuminate\Support\Str;
use Pieldefoca\Lux\Facades\Pages;
use Pieldefoca\Lux\Enum\MediaType;
use Pieldefoca\Lux\Traits\HasMedia;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Eloquent\Model;
use Pieldefoca\Lux\Models\PageComponent;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Page extends Model
{
    use HasFactory;
    use HasTranslations;
    use HasMedia;

    protected $table = 'lux_pages';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $guarded = [];

    protected $casts = [
        'visible' => 'boolean',
    ];

    public $translatable = ['slug', 'seo_title', 'seo_description'];

    public function scopePublished($query)
    {
        return $query->where('visible', true);
    }

    public function components()
    {
        return $this->belongsToMany(PageComponent::class, 'lux_page_uses_components', 'page_id', 'page_component_id')
            ->withPivot(['lang', 'content']);
    }

    public function isControllerPage()
    {
        return !is_null($this->controller);
    }

    public function isDynamic()
    {
        return Str::of($this->slug)->isMatch('/.*{*}.*/');
    }

    public function localizedUrl($locale, $params = [])
    {
        return route("{$this->id}.{$locale}", $params);
    }

    public function langFilePath(): Attribute
    {
        return Attribute::make(
            get: fn() => str($this->id)->replace('.', '/')->toString() . '.php',
        );
    }

    public function screenshotUrl(): Attribute
    {
        return Attribute::make(
            get: fn() => config('app.url') . "/img/screenshots/{$this->id}.png"
        );
    }

    public function getLangFileRelativePath(): string
    {
        $splits = explode('.', $this->id);

        if(count($splits) > 1) {
            $folders = array_slice($splits, 0, count($splits) - 1);

            return str(implode('/', $folders))->trim('/')->toString();
        }

        return '';
    }

    public function langFileKey(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->id,
        );
    }

    public function getLangFilename(bool $withExtension = false): string
    {
        $splits = explode('.', $this->id);

        $filename = $this->id;

        if(count($splits) > 1) $filename = end($splits);

        return str($filename)->when($withExtension, fn($str) => $str->append('.php'))->toString();
    }

    public function hasLangFileFor($locale)
    {
        $relativePath = $this->getLangFileRelativePath();

        $filename = $this->getLangFilename(withExtension: true);

        $path = str('')
            ->when(!empty($relativePath), function($str) use($relativePath) {
                $str->append("/{$relativePath}");
            })
            ->append($filename);

        return File::exists(lang_path($locale . '/' . $path));
    }

    public function createLangFile(string $locale)
    {
        $this->createLangFilePath($locale);

        $fallbackLocale = config('lux.fallback_locale');

        if($this->hasLangFileFor($fallbackLocale)) {
            $from = lang_path($fallbackLocale . '/' . $this->getLangFileRelativePath() . '/' . $this->getLangFilename() . '.php');
            $to = lang_path($locale . '/' . $this->getLangFileRelativePath() . '/' . $this->getLangFilename() . '.php');
            copy($from, $to);
        } else {
            $langFilePathname = lang_path($fallbackLocale . '/' . $this->getLangFileRelativePath() . '/' . $this->getLangFilename() . '.php');
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

    public function isLegalPage(): bool
    {
        $legalPages = ['accessibility', 'privacy-policy', 'cookies-policy', 'legal-notice'];

        return in_array($this->id, $legalPages);
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

    public function getImageOrCreate($key, $locale = null)
    {
        if(is_null($locale)) $locale = app()->currentLocale();

        $media = $this->getMedia('media', locale: $locale, key: $key);

        if($media->isEmpty()) {
            $ids = [1];

            $this->addMedia($ids)
                ->forLocale($locale)
                ->withKey($key)
                ->toCollection('media');

            return $this->getMedia('media', locale: $locale, key: $key)->first();
        }

        return $media->first();
    }

    public function getSlugParams()
    {
        return str($this->slug)
            ->matchAll('/{.*}/')
            ->map(fn($param) => Str::unwrap($param, '{', '}'));
    }

    public function getSlugRegex()
    {
        return str($this->slug)
            ->replaceMatches('/\//', '\/')
            ->replaceMatches('/{.*}/', '([^\/]*)')
            ->prepend('/^')
            ->append('$/')
            ->toString();
    }

    public function registerMediaCollections()
    {
        $this->addMediaCollection('media');
    }
}

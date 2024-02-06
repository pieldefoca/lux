@props([
    'key',
    'translatable' => false,
])

@php
use Pieldefoca\Lux\Models\Page;
use Pieldefoca\Lux\Models\Media;
use Pieldefoca\Lux\Models\Locale;
use Illuminate\Support\Facades\DB;

$pageKey = explode('.', $key)[0];
$isComponentImage = $pageKey === 'component';

$media = null;

if($isComponentImage) {
    $mediables = DB::table('lux_mediables')->where('key', $key)->get();

    if($mediables->isEmpty()) {
        foreach(Locale::all() as $locale) {
            DB::table('lux_mediables')->insert([
                'lux_media_id' => 1,
                'lux_mediable_id' => null,
                'lux_mediable_type' => 'BladeComponent',
                'collection' => null,
                'locale' => $locale->code,
                'key' => $key,
            ]);
        }

        $media = Media::find(1);
    } else {
        $mediable = $mediables->where('locale', app()->currentLocale())->first();
    
        if(is_null($mediable)) {
            $mediable = $mediables->where('locale', Locale::default()->code)->first();
        }

        $media = Media::find($mediable->lux_media_id);
    }

} else {
    $page = Page::where('key', $pageKey)->first();

    $media = $page->getImageOrCreate($key);
}

$url = $media->getUrl();
$alt = $media->alt;
$title = $media->title;
@endphp

<img src="{{ $url }}" alt="{{ $alt }}" title="{{ $title }}" {{ $attributes }} />
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

$page = Page::where('key', $pageKey)->first();

$mediables = DB::table('lux_mediables')
    ->where('key', $key)
    ->get();

if($mediables->isEmpty()) {
    if($translatable) {
        foreach(Locale::all() as $locale) {
            DB::table('lux_mediables')->insert([
                'lux_media_id' => 1,
                'lux_mediable_id' => $isComponentImage ? null : $page->id,
                'lux_mediable_type' => $isComponentImage ? 'BladeComponent' : 'Pieldefoca\Lux\Models\Page',
                'collection' => $isComponentImage ? null : 'media',
                'locale' => $locale->code,
                'key' => $key,
            ]);
        }
    } else {
        DB::table('lux_mediables')->insert([
            'lux_media_id' => 1,
            'lux_mediable_id' => $isComponentImage ? null : $page->id,
            'lux_mediable_type' => $isComponentImage ? 'BladeComponent' : 'Pieldefoca\Lux\Models\Page',
            'collection' => $isComponentImage ? null : 'media',
            'locale' => null,
            'key' => $key,
        ]);
    }

    $url = Media::first()->getUrl();
} else {
    if($translatable) {
        $mediable = $mediables->where('locale', app()->currentLocale())->first();
    
        if(is_null($mediable)) {
            $mediable = $mediables->where('locale', Locale::default()->code)->first();
        }
    } else {
        $mediable = $mediables->where('locale', null)->first();
    }

    $url = Media::find($mediable->lux_media_id)->getUrl();
}
@endphp

<img src="{{ $url }}" {{ $attributes }} />
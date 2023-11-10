<?php

namespace Pieldefoca\Lux\Livewire\Blog\Posts;

use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Livewire\Attributes\Rule;
use Livewire\WithFileUploads;
use Pieldefoca\Lux\Models\Post;
use Pieldefoca\Lux\Models\Locale;
use Pieldefoca\Lux\Models\Slider;
use Pieldefoca\Lux\Traits\HasSlug;
use Pieldefoca\Lux\Livewire\LuxForm;
use Illuminate\Validation\Rules\Enum;
use Pieldefoca\Lux\Enum\SliderPosition;
use Pieldefoca\Lux\Models\BlogCategory;
use Pieldefoca\Lux\Traits\HasMediaFields;
use Pieldefoca\Lux\Livewire\Attributes\Media;
use Pieldefoca\Lux\Livewire\Attributes\Slugify;
use Pieldefoca\Lux\Livewire\Attributes\MediaGallery;
use Pieldefoca\Lux\Livewire\Attributes\Translatable;

class Form extends LuxForm
{
    use WithFileUploads;
    use HasMediaFields;

    public ?Post $post;

    #[Media(collection: 'featuredImage', translatable: true)]
    public $featuredImage = [];

    #[Translatable(message: 'Escribe un título para el post')]
    public $title;

    #[Translatable]
    public $slug;

    public $categories = [];
    public $categoryOptions;

    #[Translatable]
    public $body = [];

    #[MediaGallery('gallery')]
    public $gallery = [];

    public function mount(): void
    {
        if(isset($this->post)) {
            $this->edit();
        } else {
            $this->create();
        }

        $this->categoryOptions = BlogCategory::active()->get()->map(fn($c) => ['label' => $c->name, 'value' => $c->id])->toArray();
    }

    public function updatedTitle($value, $locale)
    {
        if(! $this->editing) {
            if(empty($locale)) {
                $this->slug = Str::slug($value);
            } else {
                $this->slug[$locale] = Str::slug($value);
            }
        }
    }

    public function edit()
    {
        $this->editing = true;
    }

    public function create()
    {
        $this->editing = false;
    }

    #[On('save-post')]
    public function save(): void
    {
        dd($this->featuredImage);
        $this->validate();

        if($this->editing) {

        } else {
            $this->post = Post::create([
                'author_id' => auth()->user()->id,
                'title' => $this->title,
                'slug' => $this->slug,
                'body' => $this->body,
            ]);
        }

        $this->notifySuccess($this->editing ? '🤙🏾 Has actualizado el post correctamente' : '👍🏽 Has creado el post correctamente');
    }

    public function render()
    {
        return view('lux::livewire.blog.posts.form');
    }
}

<?php

namespace Pieldefoca\Lux\Livewire\Blog\Categories;

use Livewire\Attributes\On;
use Livewire\Attributes\Rule;
use Pieldefoca\Lux\Models\Slider;
use Illuminate\Validation\Rules\Enum;
use Pieldefoca\Lux\Livewire\LuxModal;
use Pieldefoca\Lux\Enum\SliderPosition;
use Pieldefoca\Lux\Models\BlogCategory;
use Pieldefoca\Lux\Livewire\Attributes\Media;
use Pieldefoca\Lux\Traits\HasMediaFields;

class FormModal extends LuxModal
{
    use HasMediaFields;

    public $editing = false;
    public ?BlogCategory $category;

    #[Media(collection: 'image', translatable: true)]
    #[Rule(['image' => 'exclude'])]
    public $image = [];

    #[Rule('required', message: 'Escribe un nombre')]
    public $name = [];

    #[Rule('required', message: 'Escribe una url')]
    public $slug = [];

    #[Rule('nullable|boolean')]
    public $active = true;

    public function updatedName()
    {
        $this->slug[$this->currentLocaleCode] = str($this->name[$this->currentLocaleCode])->slug()->toString();
    }

    #[On('new-blog-category')]
    public function newBlogCategory(): void
    {
        $this->editing = false;

        $this->category = null;
        $this->name = [];
        $this->slug = [];
        $this->active = true;

        $this->show();
    }

    #[On('edit-blog-category')]
    public function editBlogCategory(BlogCategory $category): void
    {
        $this->editing = true;

        $this->category = $category;
        $this->initMediaFields($category);
        $this->name = $category->getTranslations('name');
        $this->slug = $category->getTranslations('slug');
        $this->active = $category->active;

        $this->show();
    }

    public function save()
    {
        $validated = $this->validate();

        if($this->editing) {
            $this->category->update($validated);
        } else {
            $this->category = BlogCategory::create($validated);
        }

        $this->saveMediaFields($this->category);

        $this->notifySuccess($this->editing ? '🤙🏾 Has editado la categoría correctamente' : '👍🏽 Has creado la categoría correctamente');

        $this->dispatch('blog-categories-updated');

        $this->category = null;
        $this->hide();
    }

    public function render()
    {
        return view('lux::livewire.blog.categories.form-modal');
    }
}

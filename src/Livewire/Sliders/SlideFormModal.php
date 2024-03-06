<?php

namespace Pieldefoca\Lux\Livewire\Sliders;

use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Illuminate\Validation\Rules\Enum;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;
use Pieldefoca\Lux\Enum\SliderPosition;
use Pieldefoca\Lux\Livewire\Attributes\Media;
use Pieldefoca\Lux\Livewire\LuxModal;
use Pieldefoca\Lux\Models\Slide;
use Pieldefoca\Lux\Models\Slider;
use Pieldefoca\Lux\Traits\HasMediaFields;

class SlideFormModal extends LuxModal
{
    use HasMediaFields;

    public $editing = false;
    public Slider $slider;
    public ?Slide $slide;

    #[Media(collection: 'background', translatable: true)]
    public $background = [];

    public $showTitle;
    public $title = [];

    public $showSubtitle;
    public $subtitle = [];

    public $showAction;
    public $action_text = [];
    public $action_link = [];

    public function mount()
    {
        $fieldsConfig = collect(config('lux.sliders.fields'));

        $this->showTitle = $fieldsConfig->contains('title');
        $this->showSubtitle = $fieldsConfig->contains('subtitle');
        $this->showAction = $fieldsConfig->contains('action');
    }

    #[On('new-slide')]
    public function newSlide(): void
    {
        $this->editing = false;
        $this->slide = null;
        $this->title = [];
        $this->subtitle = [];
        $this->action_text = [];
        $this->action_link = [];
        $this->background = [];

        $this->show();
    }

    #[On('edit-slide')]
    public function editSlide(Slide $slide): void
    {
        $this->editing = true;
        $this->slide = $slide;
        $this->initMediaFields($slide);
        $this->title = $slide->getTranslations('title');
        $this->subtitle = $slide->getTranslations('subtitle');
        $this->action_text = $slide->getTranslations('action_text');
        $this->action_link = $slide->getTranslations('action_link');

        $this->show();
    }

    public function save(): void
    {
        $validated = $this->validate();

        if($this->editing) {
            $this->slide->update($validated);
        } else {
            $this->slide = Slide::create(array_merge($validated, ['slider_id' => $this->slider->id]));
        }

        $this->slide->addMedia($this->background)->saveTranslations()->toCollection('background');

        if($this->editing) {
            $this->notifySuccess('🤙🏾 Has actualizado la diapositiva correctamente');
        }

        $this->dispatch('slides-updated');

        $this->hide();
    }

    public function rules(): array
    {
        return [
            'title' => ['nullable'],
            'subtitle' => ['nullable'],
            'action_text' => ['nullable'],
            'action_link' => ['nullable'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Escribe un nombre para el slider',
            'position.required' => 'Elige la posición del slider',
            'position.enum' => 'Elige una posición válida',
        ];
    }

    public function render()
    {
        return view('lux::livewire.sliders.slide-form-modal');
    }
}

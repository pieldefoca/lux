<?php

namespace Pieldefoca\Lux\Livewire\Sliders;

use Livewire\Attributes\On;
use Livewire\Attributes\Rule;
use Livewire\WithFileUploads;
use Pieldefoca\Lux\Models\Slide;
use Livewire\Attributes\Computed;
use Pieldefoca\Lux\Models\Locale;
use Pieldefoca\Lux\Models\Slider;
use Pieldefoca\Lux\Livewire\LuxForm;
use Illuminate\Validation\Rules\Enum;
use App\Enum\SliderPosition;
use Pieldefoca\Lux\Traits\HasMediaFields;
use Pieldefoca\Lux\Livewire\Attributes\Media;
use Pieldefoca\Lux\Livewire\Attributes\Translatable;

class Form extends LuxForm
{
    use WithFileUploads;
    use HasMediaFields;

    public Slider $slider;

    public $name;

    public $position = [];

    public $showTitle;

    public $showSubtitle;

    public $showAction;

    protected $listeners = [
        'slides-updated' => '$refresh',
    ];

    public function mount(): void
    {
        $this->name = $this->slider->name;
        $this->position = $this->slider->position->value;

        $fieldsConfig = collect(config('lux.sliders.fields'));

        $this->showTitle = $fieldsConfig->contains('title');
        $this->showSubtitle = $fieldsConfig->contains('subtitle');
        $this->showAction = $fieldsConfig->contains('action');
    }

    #[Computed]
    public function onlyImage()
    {
        return !$this->showTitle && !$this->showSubtitle && !$this->showAction;
    }

    public function deleteSlide(Slide $slide)
    {
        $slide->delete();

        $this->dispatch('updated');

        $this->notifySuccess('🤙🏽 Has eliminado la diapositiva correctamente');
    }

    #[On('save-slider')]
    public function save(): void
    {
        $this->withValidator(function($validator) {
            if($validator->fails()) {
                $this->dispatch('show-error-feedback');
            }
        });
        
        $this->validate();

        $this->slider->update([
            'name' => $this->name,
            'position' => $this->position,
        ]);

        $this->notifySuccess('🤙🏾 Has actualizado el slider correctamente');
    }

    public function rules(): array
    {
        return [
            'name' => ['required'],
            'position' => ['required', new Enum(SliderPosition::class)],
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
        return view('lux::livewire.sliders.form');
    }
}

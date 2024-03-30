<?php

namespace Pieldefoca\Lux\Livewire\Sliders;

use Illuminate\Validation\Rule;
use Livewire\Attributes\On;
use Livewire\WithFileUploads;
use Pieldefoca\Lux\Livewire\LuxComponent;
use Pieldefoca\Lux\Livewire\Traits\LuxForm;
use Pieldefoca\Lux\Models\Slide;
use Livewire\Attributes\Computed;
use Pieldefoca\Lux\Models\Slider;
use Pieldefoca\Lux\Traits\HasMediaFields;

class Form extends LuxComponent
{
    use WithFileUploads;
    use HasMediaFields;
    use LuxForm;

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
        $this->position = $this->slider->position;

        $this->showTitle = $this->slider->hasTitleField();
        $this->showSubtitle = $this->slider->hasSubtitleField();
        $this->showAction = $this->slider->hasActionField();
    }

    #[Computed]
    public function onlyImage(): bool
    {
        return !$this->showTitle && !$this->showSubtitle && !$this->showAction;
    }

    public function deleteSlide(Slide $slide): void
    {
        $slide->delete();

        $this->dispatch('updated');

        $this->notifySuccess('🤙🏽 Has eliminado la diapositiva correctamente');
    }

    public function reorderSlides($orderedIds): void
    {
        foreach($orderedIds as $index => $id) {
            Slide::find($id)->update(['sort' => $index + 1]);
        }
    }

    #[On('save-slider')]
    public function save(): void
    {
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
            'position' => ['required', Rule::in(config('lux.sliders.positions'))],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Escribe un nombre para el slider',
            'position.required' => 'Elige la posición del slider',
            'position.in' => 'Elige una posición válida',
        ];
    }

    public function render()
    {
        return view('lux::livewire.sliders.form');
    }
}

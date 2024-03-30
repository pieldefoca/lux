<?php

namespace Pieldefoca\Lux\Livewire\Sliders;

use Illuminate\Validation\Rule;
use Livewire\Attributes\On;
use Pieldefoca\Lux\Enum\SliderField;
use Pieldefoca\Lux\Livewire\LuxModal;
use Pieldefoca\Lux\Models\Slider;

class FormModal extends LuxModal
{
    public Slider $slider;

    public $name;
    
    public $position;

    #[On('new-slider')]
    public function newSlider(): void
    {
        $this->name = null;
        $this->position = config('lux.sliders.positions')[0];

        $this->show();
    }

    public function save(): void
    {
        $this->validate();

        Slider::create([
            'name' => $this->name,
            'position' => $this->position,
            'fields' => [SliderField::Background, SliderField::Title, SliderField::Subtitle, SliderField::Action],
        ]);

        $this->notifySuccess('👍🏽 Has creado el slider correctamente');

        $this->dispatch('sliders-updated');

        $this->hide();
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
        return view('lux::livewire.sliders.form-modal');
    }
}

<?php

namespace Pieldefoca\Lux\Livewire\Sliders;

use Livewire\Attributes\On;
use Illuminate\Validation\Rules\Enum;
use Pieldefoca\Lux\Enum\SliderPosition;
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
        $this->position = SliderPosition::Home->value;

        $this->show();
    }

    public function save()
    {
        $this->validate();

        Slider::create([
            'name' => $this->name,
            'position' => $this->position,
        ]);

        $this->notifySuccess('👍🏽 Has creado el slider correctamente');

        $this->dispatch('sliders-updated');

        $this->hide();
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
        return view('lux::livewire.sliders.form-modal');
    }
}

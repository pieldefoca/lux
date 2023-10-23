<?php

namespace Pieldefoca\Lux\Livewire\Sliders;

use Livewire\Attributes\On;
use Livewire\Attributes\Rule;
use Livewire\WithFileUploads;
use Pieldefoca\Lux\Models\Locale;
use Pieldefoca\Lux\Models\Slider;
use Pieldefoca\Lux\Livewire\LuxForm;
use Illuminate\Validation\Rules\Enum;
use Pieldefoca\Lux\Enum\SliderPosition;
use Pieldefoca\Lux\Traits\HasMediaFields;
use Pieldefoca\Lux\Livewire\Attributes\Media;
use Pieldefoca\Lux\Livewire\Attributes\Translatable;

class Form extends LuxForm
{
    use WithFileUploads;
    use HasMediaFields;
    
    public Slider $slider;

    #[Media(collection: 'images', translatable: true)]
    public $image = [];

    #[Rule('required', message: 'Escribe un nombre')]
    public $name;

    public $position = [];
    public $url = 'http://lux-app.test/img/fire.svg';

    protected $listeners = [
        'slides-updated' => '$refresh',
    ];

    public function mount(): void
    {
        $this->name = $this->slider->name;
        $this->position = $this->slider->position->value;
    }

    #[On('save-slider')]
    public function save(): void
    {
        dd($this->getMediaProperties());
        $this->validate();

        if($this->editing) {
            $this->slider->update([
                'name' => $this->name,
                'position' => $this->position,
            ]);
        }

        $this->notifySuccess('🤙🏾 Has actualizado el slider correctamente');
    }

    public function rules(): array
    {
        return [
            // 'name' => ['required'],
            'position' => ['required', new Enum(SliderPosition::class)],
        ];
    }

    // public function messages(): array
    // {
    //     return [
    //         'name.required' => 'Escribe un nombre para el slider',
    //         'position.required' => 'Elige la posición del slider',
    //         'position.enum' => 'Elige una posición válida',
    //     ];
    // }

    public function render()
    {
        return view('lux::livewire.sliders.form');
    }
}

<?php

namespace Pieldefoca\Lux\Livewire\Pages;

use Livewire\Attributes\On;
use Pieldefoca\Lux\Models\Page;
use Pieldefoca\Lux\Models\Slider;
use Illuminate\Validation\Rules\Enum;
use Pieldefoca\Lux\Livewire\LuxModal;
use Pieldefoca\Lux\Enum\SliderPosition;
use Pieldefoca\Lux\Traits\HasMediaFields;
use Pieldefoca\Lux\Livewire\Attributes\Media;

class PageMediaFormModal extends LuxModal
{
    use HasMediaFields;

    public Page $page;

    public $key;

    #[Media(collection: 'media', translatable: false)]
    public $media;

    public function updatedKey($value)
    {
        $this->key = str($value)->slug()->toString();
    }

    #[On('add-page-media')]
    public function addPageMedia(): void
    {
        $this->show();
    }

    public function save()
    {
        dd($this->media);
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
            'key' => ['required'],
            'media' => ['required'],
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
        return view('lux::livewire.pages.page-media-form-modal');
    }
}

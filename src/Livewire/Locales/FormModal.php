<?php

namespace Pieldefoca\Lux\Livewire\Locales;

use Pieldefoca\Lux\Models\Locale;
use Livewire\Attributes\On;
use Pieldefoca\Lux\Blog\Facades\BlogSettings;
use Pieldefoca\Lux\Livewire\LuxModal;
use Pieldefoca\Lux\Traits\HasMediaFields;
use Pieldefoca\Lux\Blog\Models\BlogCategory;
use Pieldefoca\Lux\Livewire\Attributes\Media;
use Pieldefoca\Lux\Livewire\Attributes\Translatable;

class FormModal extends LuxModal
{
    use HasMediaFields;

    public $editing = false;

    public ?Locale $locale;

    #[Translatable]
    public $name = [];

    public $code;

    public $active = true;

    #[On('new-locale')]
    public function newLocale(): void
    {
        $this->editing = false;

        $this->locale = null;
        $this->name = [];
        $this->code = null;
        $this->active = true;

        $this->show();
    }

    #[On('edit-locale')]
    public function editLocale(Locale $locale): void
    {
        $this->editing = true;

        $this->locale = $locale;
        $this->name = $locale->getTranslations('name');
        $this->code = $locale->code;
        $this->active = $locale->active;

        $this->show();
    }

    public function save()
    {
        $validated = $this->validate();

        $validated['flag'] = "{$this->code}.svg";

        if($this->editing) {
            $this->locale->update($validated);
        } else {
            $this->locale = Locale::create($validated);
        }

        $this->notifySuccess($this->editing ? '🤙🏾 Has editado el idioma correctamente' : '👍🏽 Has creado el idioma correctamente');

        $this->dispatch('locales-updated');

        $this->locale = null;
        $this->hide();
    }

    public function rules()
    {
        return [
            'name.*' => ['required'],
            'code' => ['required'],
            'active' => ['nullable', 'boolean'],
        ];
    }

    public function messages()
    {
        return [
            'name.*.required' => 'Escribe un nombre',
            'code.required' => 'Escribe el código del idioma',
            'active.boolean' => 'Elige un valor válido',
        ];
    }

    public function render()
    {
        return view('lux::livewire.locales.form-modal');
    }
}

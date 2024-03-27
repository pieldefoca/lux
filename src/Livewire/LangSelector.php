<?php

namespace Pieldefoca\Lux\Livewire;

use Livewire\Component;

class LangSelector extends Component
{
    public $lang;

    public function mount()
    {
        $this->lang = session('luxLocale', config('lux.fallback_locale'));
    }

    public function select($locale)
    {
        session(['luxLocale' => $locale]);
    }

    public function render()
    {
        return view('lux::livewire.lang-selector');
    }
}